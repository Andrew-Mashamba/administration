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

class SaccoProvisioner
{
    private $baseDir;
    private $instancesDir;
    private $baseUrl = 'http://22.32.241.42';

    public function __construct()
    {
        $this->baseDir = dirname(base_path());
        $this->instancesDir = "{$this->baseDir}/instances";
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
        $vhostConfig = <<<CONF
<VirtualHost *:80>
    ServerName {$this->baseUrl}/{$alias}
    DocumentRoot "{$targetPath}/public"

    <Directory "{$targetPath}/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog "logs/{$alias}-error.log"
    CustomLog "logs/{$alias}-access.log" combined
</VirtualHost>
CONF;

        $vhostPath = "/etc/httpd/conf.d/{$alias}.conf";
        exec("sudo tee {$vhostPath} << 'EOL'\n{$vhostConfig}\nEOL", $output, $returnCode);

        if ($returnCode !== 0) {
            throw new Exception("Failed to create Apache virtual host configuration");
        }

        // Reload Apache
        exec("sudo systemctl reload httpd", $output, $returnCode);
        if ($returnCode !== 0) {
            throw new Exception("Failed to reload Apache");
        }

        Log::info("Apache configured for {$alias}");
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

        Mail::send('emails.welcome', [
            'email' => $email,
            'password' => $password,
            'url' => $url
        ], function($message) use ($email) {
            $message->to($email)
                   ->subject('Welcome to Your New SACCO Instance');
        });

        Log::info("Welcome email sent to {$email}");
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

            Log::info("Starting provisioning process", [
                'alias' => $alias,
                'database' => $dbName,
                'host' => $dbHost
            ]);

            $baseTemplate = "{$this->baseDir}/template_v3";
            $targetPath = "{$this->instancesDir}/{$alias}";

            // Ensure proper permissions
            if (!File::exists($this->instancesDir)) {
                File::makeDirectory($this->instancesDir, 0755, true);
                exec("chmod -R 755 {$this->instancesDir}");
                Log::info("Created directory: {$this->instancesDir}");
            }

            Log::info("Step 1: Cloning template");
            $this->copyDirectoryWithProgress($baseTemplate, $targetPath);

            Log::info("Step 2: Creating database");
            $this->createRemoteDatabase($dbHost, $dbName, $dbUser, $dbPassword);

            Log::info("Step 3: Generating .env file");
            $this->generateEnvFile($targetPath, $dbName, $dbHost, $dbUser, $dbPassword);

            Log::info("Step 4: Updating Livewire config");
            $this->updateLivewireConfig($targetPath, $alias);

            Log::info("Step 5: Configuring Apache");
            $this->configureApache($alias, $targetPath);

            Log::info("Step 6: Running post-installation commands");
            $this->runPostInstallationCommands($targetPath);

            Log::info("Step 7: Creating default users");
            $this->createDefaultUsers($targetPath, $alias, $managerEmail, $itEmail);

            Log::info("Provisioning completed successfully", [
                'alias' => $alias,
                'path' => $targetPath,
                'url' => "{$this->baseUrl}/{$alias}"
            ]);

            return [
                'success' => true,
                'path' => $targetPath,
                'alias' => $alias,
                'url' => "{$this->baseUrl}/{$alias}"
            ];

        } catch (Exception $e) {
            Log::error("Provisioning failed", [
                'alias' => $alias ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (isset($alias) && isset($targetPath)) {
                $this->cleanupFailedProvision($alias, $targetPath);
            }

            throw $e;
        }
    }

    private function generateEnvFile(string $targetPath, string $dbName, string $dbHost, string $dbUser, string $dbPassword): void
    {
        $envTemplatePath = "{$this->baseDir}/template_v3/.env";
        if (!File::exists($envTemplatePath)) {
            throw new Exception("Template .env file not found at: {$envTemplatePath}");
        }

        $envContent = str_replace(
            [
                'DB_CONNECTION=mysql',
                'DB_DATABASE=laravel',
                'DB_USERNAME=root',
                'DB_PASSWORD=',
                'DB_HOST=127.0.0.1',
                'DB_PORT=3306',
            ],
            [
                'DB_CONNECTION=pgsql',
                "DB_DATABASE={$dbName}",
                "DB_USERNAME={$dbUser}",
                "DB_PASSWORD={$dbPassword}",
                "DB_HOST={$dbHost}",
                'DB_PORT=5432',
            ],
            File::get($envTemplatePath)
        );

        File::put("{$targetPath}/.env", $envContent);
        exec("chmod 644 {$targetPath}/.env");
    }

    private function runPostInstallationCommands(string $targetPath): void
    {
        $commands = [
            'composer install --no-interaction --optimize-autoloader',
            'php artisan migrate --force',
            'php artisan db:seed',
            'npm install',
            'npm run build',
            'chown -R www-data:www-data storage bootstrap/cache',
            'chmod -R 775 storage bootstrap/cache'
        ];

        foreach ($commands as $command) {
            exec("cd {$targetPath} && {$command}", $output, $returnCode);
            if ($returnCode !== 0) {
                $errorOutput = implode("\n", $output);
                Log::error("Command failed: {$command}", [
                    'output' => $errorOutput,
                    'returnCode' => $returnCode
                ]);
                throw new Exception("Failed to execute command: {$command}. Error: {$errorOutput}");
            }
            Log::info("Successfully executed: {$command}");
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
