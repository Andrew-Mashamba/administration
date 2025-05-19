<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Exception;
use Symfony\Component\Process\Process;

class ApacheConfigService
{
    private $apacheConfigDir;
    private $apacheSitesDir;
    private $workingDir;

    public function __construct()
    {
        $this->apacheConfigDir = '/etc/httpd/conf.d';
        $this->apacheSitesDir = '/etc/httpd/conf.d';
        $this->workingDir = '/var/www/html';
    }

    public function configure(string $alias, string $targetPath): void
    {
        try {
            // // Validate paths
            // if (!File::exists($targetPath)) {
            //     throw new Exception("Target path does not exist: {$targetPath}");
            // }

            // // Ensure Apache directories exist
            // if (!File::exists($this->apacheConfigDir)) {
            //     throw new Exception("Apache configuration directory does not exist: {$this->apacheConfigDir}");
            // }

            // // Create Apache configuration
            // $configContent = $this->generateApacheConfig($alias, $targetPath);
            // $configPath = "{$this->apacheConfigDir}/{$alias}.conf";

            // // Write configuration file using sudo
            // $tempFile = tempnam(sys_get_temp_dir(), 'apache_');
            // if (!File::put($tempFile, $configContent)) {
            //     throw new Exception("Failed to write temporary configuration file");
            // }

            // // Move the file to Apache config directory using sudo
            // $process = new Process(['sudo', 'mv', $tempFile, $configPath]);
            // $process->setWorkingDirectory($this->workingDir);
            // $process->setTimeout(30);
            // $process->mustRun();

            // // Set proper permissions
            // $process = new Process(['sudo', 'chown', 'root:root', $configPath]);
            // $process->setWorkingDirectory($this->workingDir);
            // $process->setTimeout(30);
            // $process->mustRun();

            // $process = new Process(['sudo', 'chmod', '644', $configPath]);
            // $process->setWorkingDirectory($this->workingDir);
            // $process->setTimeout(30);
            // $process->mustRun();

            // // Test Apache configuration
            // $process = new Process(['sudo', 'apachectl', '-t']);
            // $process->setWorkingDirectory($this->workingDir);
            // $process->setTimeout(30);
            // $process->mustRun();

            // // Reload Apache
            // $process = new Process(['sudo', 'systemctl', 'reload', 'httpd']);
            // $process->setWorkingDirectory($this->workingDir);
            // $process->setTimeout(30);
            // $process->mustRun();

            Log::info("Apache configuration completed successfully", [
                'alias' => $alias,
                'config_path' => "configPath"
            ]);

        } catch (Exception $e) {
            Log::error("Apache configuration failed", [
                'error' => $e->getMessage(),
                'alias' => $alias,
                'target_path' => $targetPath
            ]);
            throw $e;
        }
    }

    public function removeConfig(string $alias): void
    {
        try {
            $configPath = "{$this->apacheConfigDir}/{$alias}.conf";

            // Remove configuration file using sudo
            if (File::exists($configPath)) {
                $process = new Process(['sudo', 'rm', $configPath]);
                $process->setWorkingDirectory($this->workingDir);
                $process->setTimeout(30);
                $process->mustRun();
            }

            // Reload Apache
            $process = new Process(['sudo', 'systemctl', 'reload', 'httpd']);
            $process->setWorkingDirectory($this->workingDir);
            $process->setTimeout(30);
            $process->mustRun();

            Log::info("Apache configuration removed successfully", [
                'alias' => $alias
            ]);

        } catch (Exception $e) {
            Log::error("Failed to remove Apache configuration", [
                'error' => $e->getMessage(),
                'alias' => $alias
            ]);
            throw $e;
        }
    }

    private function generateApacheConfig(string $alias, string $targetPath): string
    {
        return <<<EOT
<VirtualHost *:80>
    ServerName {$alias}.nbcsaccos.co.tz
    DocumentRoot {$targetPath}/public

    <Directory {$targetPath}/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/{$alias}-error.log
    CustomLog \${APACHE_LOG_DIR}/{$alias}-access.log combined
</VirtualHost>
EOT;
    }
}
