<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PDO;
use Exception;
use Jackiedo\DotenvEditor\DotenvEditor;
use Symfony\Component\Process\Process;


class SaccoProvisioner
{
    private $baseDir;
    private $instancesDir;
    private $baseUrl = 'http://172.240.241.180';
    private $progressCallback;

    public function __construct()
    {
        $this->baseDir = dirname(base_path());
        $this->instancesDir = "{$this->baseDir}/instances";
    }

    public function setProgressCallback(callable $callback)
    {
        $this->progressCallback = $callback;
    }

    private function notifyProgress($step, $message, $data = [])
    {
        if ($this->progressCallback) {
            call_user_func($this->progressCallback, $step, $message, $data);
        }
    }

    private function copyDirectoryWithProgress(string $source, string $destination): void
    {
        set_time_limit(0);
        Log::info("Starting directory copy from {$source} to {$destination}");

        if (!File::exists($source)) {
            throw new Exception("Source directory not found: {$source}");
        }

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
            Log::info("Created destination directory: {$destination}");
        }

        // Use rsync if available for better performance
        exec('which rsync', $output, $returnCode);
        if ($returnCode === 0) {
            $rsyncCommand = "rsync -av --progress {$source}/ {$destination}/";
            exec($rsyncCommand, $output, $returnCode);
            if ($returnCode === 0) {
                Log::info("Directory copied successfully using rsync");
                return;
            }
            Log::warning("Rsync failed, falling back to PHP copy");
        }

        // Fallback to PHP's copy if rsync fails
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $totalFiles = iterator_count($iterator);
        $currentFile = 0;

        foreach ($iterator as $item) {
            $currentFile++;
            $relativePath = str_replace($source . DIRECTORY_SEPARATOR, '', $item->getPathname());
            $target = $destination . DIRECTORY_SEPARATOR . $relativePath;

            if ($item->isDir()) {
                if (!File::exists($target)) {
                    File::makeDirectory($target, 0755, true);
                }
            } else {
                File::copy($item, $target);
            }

            if ($currentFile % 10 === 0) {
                $progress = round(($currentFile / $totalFiles) * 100, 2);
                //Log::info("Copying files... {$progress}% complete");
            }
        }

        Log::info("Directory copy completed: {$totalFiles} files copied");
    }

    private function updateLivewireConfig(string $targetPath, string $alias): void
    {
        $livewireConfigPath = "{$targetPath}/config/livewire.php";
        if (!File::exists($livewireConfigPath)) {
            throw new Exception("Livewire config file not found at: {$livewireConfigPath}");
        }

        $config = require $livewireConfigPath;
        $config['asset_url'] = "{$this->baseUrl}/{$alias}";

        $content = "<?php\n\nreturn " . var_export($config, true) . ";\n";
        File::put($livewireConfigPath, $content);
        Log::info("Updated Livewire config for {$alias}");
    }



private function configureApache(string $alias, string $targetPath): void
{
    try {
        $apacheService = new ApacheConfigService();
        $apacheService->configure($alias, $targetPath);
    } catch (Exception $e) {
        Log::error("Apache configuration failed: " . $e->getMessage());
        throw $e;
    }
}

    private function createDefaultUsers(string $targetPath, string $alias, ?string $managerEmail = null, ?string $itEmail = null): void
    {
        // Create users in the instance's database
        $users = [];

        if ($managerEmail) {
            $users[] = [
                'email' => $managerEmail,
                'password' => '1234567890',
                'name' => 'SACCOS Manager'
            ];
        }

        if ($itEmail) {
            $users[] = [
                'email' => $itEmail,
                'password' => '1234567891',
                'name' => 'IT Administrator'
            ];
        }

        // Create a temporary .env file with the instance's database credentials
        $envPath = "{$targetPath}/.env";
        $envContent = File::get($envPath);

        // Extract database credentials from .env
        preg_match('/DB_CONNECTION=(.*)/', $envContent, $connection);
        preg_match('/DB_HOST=(.*)/', $envContent, $host);
        preg_match('/DB_PORT=(.*)/', $envContent, $port);
        preg_match('/DB_DATABASE=(.*)/', $envContent, $database);
        preg_match('/DB_USERNAME=(.*)/', $envContent, $username);
        preg_match('/DB_PASSWORD=(.*)/', $envContent, $password);

        // Create a temporary database connection for the instance
        config([
            'database.connections.instance' => [
                'driver' => $connection[1] ?? 'pgsql',
                'host' => $host[1] ?? '127.0.0.1',
                'port' => $port[1] ?? '5432',
                'database' => $database[1] ?? '',
                'username' => $username[1] ?? '',
                'password' => $password[1] ?? '',
            ]
        ]);

        // Use the instance's database connection
        foreach ($users as $user) {
            DB::connection('instance')->table('users')->insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Send welcome email
            $this->sendWelcomeEmail($user['email'], $user['password'], $alias);
        }

        Log::info("Default users created for {$alias}");
    }

    private function sendWelcomeEmail(string $email, string $password, string $alias): void
    {
        $url = "{$this->baseUrl}/{$alias}";
        $instanceDomain = "{$alias}.nbcsaccos.co.tz";

        try {
            Mail::to($email)
                ->queue(new \App\Mail\WelcomeEmail([
                    'email' => $email,
                    'password' => $password,
                    'url' => "http://{$instanceDomain}",
                    'name' => explode('@', $email)[0] // Extract name from email
                ]));

            Log::info("Welcome email queued for {$email}");
        } catch (Exception $e) {
            Log::error("Failed to queue welcome email", [
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Store failed email in database for retry
            DB::table('failed_emails')->insert([
                'email' => $email,
                'template' => 'emails.welcome',
                'data' => json_encode([
                    'email' => $email,
                    'password' => $password,
                    'url' => "https://{$instanceDomain}",
                    'name' => explode('@', $email)[0]
                ]),
                'error' => $e->getMessage(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function provision(string $alias, string $dbName, string $dbHost, string $dbUser = 'postgres', string $dbPassword = 'postgres', ?string $managerEmail = null, ?string $itEmail = null): array
    {
        try {
            set_time_limit(0);
            ini_set('memory_limit', '512M');

            if (empty($alias) || empty($dbName) || empty($dbHost)) {
                throw new Exception("Alias, database name, and database host are required");
            }

            $alias = Str::slug($alias);
            $dbName = Str::slug($dbName, '_');

            $this->notifyProgress('started', 'Starting provisioning process', [
                'alias' => $alias,
                'database' => $dbName,
                'host' => $dbHost
            ]);

            $baseTemplate = "{$this->baseDir}/template";
            $targetPath = "{$this->instancesDir}/{$alias}";

            // Ensure proper permissions
            if (!File::exists($this->instancesDir)) {
                File::makeDirectory($this->instancesDir, 0755, true);
                exec("chmod -R 755 {$this->instancesDir}");
                $this->notifyProgress('directory_created', 'Created instances directory');
            }

            $this->notifyProgress('cloning', 'Cloning template');
            $this->copyDirectoryWithProgress($baseTemplate, $targetPath);

            $this->notifyProgress('database', 'Creating database');
            $this->createRemoteDatabase($dbHost, $dbName, $dbUser, $dbPassword);

            $this->notifyProgress('env', 'Generating .env file');
            $this->generateEnvFile($targetPath, $dbName, $dbHost, $dbUser, $dbPassword, $alias);

            $this->notifyProgress('livewire', 'Updating Livewire config');
            $this->updateLivewireConfig($targetPath, $alias);

            $this->notifyProgress('apache', 'Configuring Apache');
            $this->configureApache($alias, $targetPath);

            $this->notifyProgress('post_install', 'Running post-installation commands');
            $this->runPostInstallationCommands($targetPath, $dbHost, $dbName, $dbUser, $dbPassword);

            $this->notifyProgress('users', 'Creating default users');
            $this->createDefaultUsers($targetPath, $alias, $managerEmail, $itEmail);

            $result = [
                'success' => true,
                'path' => $targetPath,
                'alias' => $alias,
                'url' => "{$this->baseUrl}/{$alias}"
            ];

            $this->notifyProgress('completed', 'Provisioning completed successfully', $result);

            return $result;

        } catch (Exception $e) {
            $this->notifyProgress('failed', 'Provisioning failed: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (isset($alias) && isset($targetPath)) {
                $this->cleanupFailedProvision($alias, $targetPath);
            }

            throw $e;
        }
    }

    private function generateEnvFile(string $targetPath, string $dbName, string $dbHost, string $dbUser, string $dbPassword, string $alias): void
    {
        $envTemplatePath = "{$this->baseDir}/template/.env.stub";
        if (!File::exists($envTemplatePath)) {
            throw new Exception("Template .env.stub file not found at: {$envTemplatePath}");
        }

        // Copy template to target path
        File::copy($envTemplatePath, "{$targetPath}/.env");

        // Initialize DotenvEditor with proper container and config
        $editor = app(DotenvEditor::class, []);
        $editor->load("{$targetPath}/.env");

        // Set database configuration
        $editor->setKeys([
            'DB_CONNECTION' => 'pgsql',
            'DB_HOST' => $dbHost,
            'DB_PORT' => '5432',
            'DB_DATABASE' => $dbName,
            'DB_USERNAME' => $dbUser,
            'DB_PASSWORD' => $dbPassword,
        ]);

        // Set application configuration
        $editor->setKeys([
            'APP_URL' => "http://{$alias}.nbcsaccos.co.tz",
            'APP_ENV' => 'production',
            'APP_DEBUG' => 'false',
            'LOG_CHANNEL' => 'daily',
            'LOG_LEVEL' => 'info',
        ]);

        // Save changes
        $editor->save();

        // Set proper permissions
        exec("chmod 644 {$targetPath}/.env");

        Log::info("Generated .env file for {$alias} with database configuration");
    }

    private function runPostInstallationCommands(string $targetPath, string $dbHost, string $dbName, string $dbUser, string $dbPassword): void
    {
        Log::info("Starting post-installation commands", ['targetPath' => $targetPath]);

        // Store original connection and disconnect
        $originalConnection = config('database.default');
        DB::disconnect($originalConnection);

        try {
            // Configure and connect to temporary database
            config([
                'database.connections.temp_connection' => [
                    'driver' => 'pgsql',
                    'url' => env('TEMP_DB_URL'),
                    'host' => $dbHost,
                    'port' => env('TEMP_DB_PORT', '5432'),
                    'database' => $dbName,
                    'username' => $dbUser,
                    'password' => $dbPassword,
                    'charset' => env('TEMP_DB_CHARSET', 'utf8'),
                    'prefix' => '',
                    'prefix_indexes' => true,
                    'search_path' => 'public',
                    'sslmode' => 'prefer',
                ]
            ]);

            // Set as default connection and ensure it's connected
            config(['database.default' => 'temp_connection']);
            DB::purge('temp_connection');
            DB::reconnect('temp_connection');

            // Verify connection
            try {
                DB::connection('temp_connection')->getPdo();
                Log::info("Successfully connected to temporary database", [
                    'database' => $dbName,
                    'host' => $dbHost
                ]);
            } catch (Exception $e) {
                throw new Exception("Failed to connect to temporary database: " . $e->getMessage());
            }

            // Change to target directory
            $currentDir = getcwd();
            chdir($targetPath);

            // Run composer install
            // $process = Process::fromShellCommandline('composer install --no-interaction --optimize-autoloader');
            // $process->setTimeout(300);
            // $process->mustRun();

            // Run migrations using the temporary connection
            $process = Process::fromShellCommandline('php artisan migrate:fresh --force --database=temp_connection');
            $process->setTimeout(300);
            $process->mustRun();

            // Run seeders
            $process = Process::fromShellCommandline('php artisan db:seed --database=temp_connection');
            $process->setTimeout(300);
            $process->mustRun();

            // Set permissions
            $process = Process::fromShellCommandline('chown -R apache:apache storage bootstrap/cache && chmod -R 775 storage bootstrap/cache');
            $process->setTimeout(300);
            $process->mustRun();

            // Change back to original directory
            chdir($currentDir);

        } catch (Exception $e) {
            Log::error("Error during post-installation commands", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        } finally {
            // Clean up temporary connection
            DB::disconnect('temp_connection');
            DB::purge('temp_connection');

            // Restore original connection
            config(['database.default' => $originalConnection]);
            DB::reconnect($originalConnection);

            // Verify original connection is restored
            try {
                DB::connection($originalConnection)->getPdo();
                Log::info("Successfully restored original database connection", [
                    'connection' => $originalConnection
                ]);
            } catch (Exception $e) {
                Log::error("Failed to restore original database connection", [
                    'error' => $e->getMessage(),
                    'connection' => $originalConnection
                ]);
                throw new Exception("Failed to restore original database connection: " . $e->getMessage());
            }
        }
    }

    private function createRemoteDatabase(string $host, string $dbName, string $user, string $password): void
    {
        $dsn = "pgsql:host={$host};port=5432;dbname=postgres";

        try {
            Log::info("Attempting to connect to PostgreSQL server", ['host' => $host]);

            $pdo = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 30
            ]);

            Log::info("Creating database", ['database' => $dbName]);
            $pdo->exec("CREATE DATABASE \"{$dbName}\"");

        } catch (Exception $e) {
            if (Str::contains($e->getMessage(), 'already exists')) {
                Log::info("Database already exists", ['database' => $dbName]);
                return;
            }

            Log::error("Database creation failed", [
                'error' => $e->getMessage(),
                'host' => $host,
                'database' => $dbName
            ]);

            throw $e;
        }
    }

    private function cleanupFailedProvision(string $alias, string $targetPath): void
    {
        Log::info("Attempting cleanup after failed provision", ['alias' => $alias]);

        try {
            // Remove Apache configuration
            $apacheService = new ApacheConfigService();
            $apacheService->removeConfig($alias);

            // Remove instance directory if it exists
            if (File::exists($targetPath)) {
                File::deleteDirectory($targetPath);
            }

            Log::info("Cleanup completed", ['alias' => $alias]);
        } catch (Exception $e) {
            Log::error("Cleanup failed", [
                'alias' => $alias,
                'error' => $e->getMessage()
            ]);
        }
    }
}
