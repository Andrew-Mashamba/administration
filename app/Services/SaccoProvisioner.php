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
    private $workingDir = '/var/www/html/administration';

    public function __construct()
    {
        $this->baseDir = '/var/www/html';
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
        $this->notifyProgress('cloning', 'Starting directory copy');

        // Ensure source exists
        if (!File::exists($source)) {
            throw new Exception("Source directory not found: {$source}");
        }

        // Ensure destination exists with proper permissions
        if (!File::exists($destination)) {
            if (!File::makeDirectory($destination, 0755, true)) {
                throw new Exception("Failed to create destination directory: {$destination}");
            }
            // Set proper ownership
            exec("chown -R apache:apache " . escapeshellarg($destination));
            Log::info("Created destination directory: {$destination}");
        }

        // Use absolute paths
        $source = realpath($source);
        $destination = realpath($destination);

        // Use rsync if available for better performance
        exec('which rsync', $output, $returnCode);
        if ($returnCode === 0) {
            $this->notifyProgress('cloning', 'Using rsync for faster copy');
            
            try {
                $process = new Process(['rsync', '-av', '--no-relative', '--progress', $source . '/', $destination . '/']);
                $process->setTimeout(300);
                $process->run(function ($type, $buffer) {
                    if (Process::ERR === $type) {
                        Log::warning("Rsync error output: " . $buffer);
                    } else {
                        // Parse rsync progress output
                        if (preg_match('/(\d+)%\s+(\d+\.\d+[KMG]\/s)/', $buffer, $matches)) {
                            $progress = (int)$matches[1];
                            $speed = $matches[2];
                            $this->notifyProgress('cloning', "Copying files... {$progress}% complete ({$speed})", [
                                'progress' => $progress,
                                'speed' => $speed
                            ]);
                        }
                    }
                });

                if (!$process->isSuccessful()) {
                    throw new Exception("Rsync failed: " . $process->getErrorOutput());
                }

                Log::info("Directory copied successfully using rsync");
                // Set proper permissions after copy
                exec("chown -R apache:apache " . escapeshellarg($destination));
                $this->notifyProgress('cloning', 'Directory copy completed');
                return;
            } catch (Exception $e) {
                Log::warning("Rsync failed, falling back to PHP copy", [
                    'error' => $e->getMessage()
                ]);
                $this->notifyProgress('cloning', 'Falling back to PHP copy method');
            }
        }

        // Fallback to PHP's copy if rsync fails
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $totalFiles = iterator_count($iterator);
        $currentFile = 0;
        $lastProgress = 0;

        foreach ($iterator as $item) {
            $currentFile++;
            $relativePath = str_replace($source . DIRECTORY_SEPARATOR, '', $item->getPathname());
            $target = $destination . DIRECTORY_SEPARATOR . $relativePath;

            try {
                if ($item->isDir()) {
                    if (!File::exists($target)) {
                        File::makeDirectory($target, 0755, true);
                    }
                } else {
                    File::copy($item, $target);
                }

                // Update progress every 5% or every 10 files
                $progress = round(($currentFile / $totalFiles) * 100, 2);
                if ($progress >= $lastProgress + 5 || $currentFile % 10 === 0) {
                    $lastProgress = $progress;
                    $this->notifyProgress('cloning', "Copying files... {$progress}% complete", [
                        'progress' => $progress,
                        'current_file' => $currentFile,
                        'total_files' => $totalFiles
                    ]);
                }
            } catch (Exception $e) {
                Log::error("Failed to copy file", [
                    'source' => $item->getPathname(),
                    'target' => $target,
                    'error' => $e->getMessage()
                ]);
                throw new Exception("Failed to copy file {$relativePath}: " . $e->getMessage());
            }
        }

        // Set proper permissions after copy
        exec("chown -R apache:apache " . escapeshellarg($destination));

        Log::info("Directory copy completed: {$totalFiles} files copied");
        $this->notifyProgress('cloning', 'Directory copy completed successfully', [
            'total_files' => $totalFiles
        ]);
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
        // Ensure the target path exists
        if (!File::exists($targetPath)) {
            throw new Exception("Target path does not exist: {$targetPath}");
        }

        $apacheService = new ApacheConfigService();
        $apacheService->configure($alias, $targetPath);

    } catch (Exception $e) {
        Log::error("Apache configuration failed yyyyyyyyyyyyyy", [
            'error' => $e->getMessage(),
            'alias' => $alias,
            'target_path' => $targetPath
        ]);
        throw new Exception("Apache configuration failed bbbbbbbbbbbbbbb: " . $e->getMessage());
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
            try {
                DB::connection('instance')->table('users')->insertOrIgnore([
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'password' => Hash::make($user['password']),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Send welcome email
                $this->sendWelcomeEmail($user['email'], $user['password'], $alias);
            } catch (Exception $e) {
                Log::warning("Failed to create user, continuing with next user", [
                    'email' => $user['email'],
                    'error' => $e->getMessage()
                ]);
                continue;
            }
        }

        Log::info("Default users created for {$alias}");
    }

    private function sendWelcomeEmail(string $email, string $password, string $alias): void
    {
        $url = "{$this->baseUrl}/{$alias}";
        $instanceDomain = "{$alias}.zima-uat.site";
        $name = explode('@', $email)[0]; // Extract name from email

        try {
            Mail::to($email)
                ->queue(new \App\Mail\WelcomeEmail([
                    'email' => $email,
                    'password' => $password,
                    'url' => "https://{$instanceDomain}",
                    'name' => $name
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
                    'name' => $name
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

            // Ensure we have the correct working directory
            $currentDir = getcwd();
            if ($currentDir === false) {
                // If we can't get current directory, try to set it to a known good location
                chdir('/var/www/html');
            }

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
            'DB_SCHEMA' => 'public',
            'DB_SSL_MODE' => 'prefer',
        ]);

        // Set application configuration
        $editor->setKeys([
            'APP_URL' => "http://{$alias}.zima-uat.site",
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
            // Create a temporary database config file
            $tempConfigPath = "{$targetPath}/config/database.php";
            $configContent = <<<PHP
<?php

return [
    'default' => 'pgsql',
    'migrations' => 'migrations',
    'connections' => [
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => '{$dbHost}',
            'port' => '5432',
            'database' => '{$dbName}',
            'username' => '{$dbUser}',
            'password' => '{$dbPassword}',
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
            'migrations' => 'migrations',
        ],
    ],
];
PHP;
            file_put_contents($tempConfigPath, $configContent);

            // Create a temporary .env file for the artisan command
            $tempEnvPath = "{$targetPath}/.env.temp";
            $envContent = <<<ENV
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:your-app-key-here
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST={$dbHost}
DB_PORT=5432
DB_DATABASE={$dbName}
DB_USERNAME={$dbUser}
DB_PASSWORD={$dbPassword}

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
ENV;
            file_put_contents($tempEnvPath, $envContent);

            // Run migrations with the temporary .env file
            $process = new Process(['php', 'artisan', 'migrate:fresh', '--force', '--env=local']);
            $process->setWorkingDirectory($targetPath);
            $process->setEnv(['APP_ENV' => 'local']);
            $process->setTimeout(300);
            $process->mustRun();

            // Run seeders
            $process = new Process(['php', 'artisan', 'db:seed', '--env=local']);
            $process->setWorkingDirectory($targetPath);
            $process->setEnv(['APP_ENV' => 'local']);
            $process->setTimeout(300);
            $process->mustRun();

            // Set permissions
            $process = new Process(['chown', '-R', 'apache:apache', 'storage', 'bootstrap/cache']);
            $process->setWorkingDirectory($targetPath);
            $process->setTimeout(300);
            $process->mustRun();

            $process = new Process(['chmod', '-R', '775', 'storage', 'bootstrap/cache']);
            $process->setWorkingDirectory($targetPath);
            $process->setTimeout(300);
            $process->mustRun();

            // Clean up temporary files
            unlink($tempConfigPath);
            unlink($tempEnvPath);

            // Restore original connection
            config(['database.default' => $originalConnection]);
            DB::purge('pgsql');
            DB::reconnect($originalConnection);

        } catch (Exception $e) {
            Log::error("Error during post-installation commands", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
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
