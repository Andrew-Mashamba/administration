<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Exception;

class ApacheConfigService
{
    private $apacheConfigDir;
    private $apacheSitesDir;

    public function __construct()
    {
        $this->apacheConfigDir = '/etc/httpd/conf.d';
        $this->apacheSitesDir = '/etc/httpd/conf.d';
    }

    public function configure(string $alias, string $targetPath): void
    {
        try {
            // Validate paths
            if (!File::exists($targetPath)) {
                throw new Exception("Target path does not exist: {$targetPath}");
            }

            // Ensure Apache directories exist
            if (!File::exists($this->apacheConfigDir)) {
                throw new Exception("Apache configuration directory does not exist: {$this->apacheConfigDir}");
            }

            // Create Apache configuration
            $configContent = $this->generateApacheConfig($alias, $targetPath);
            $configPath = "{$this->apacheConfigDir}/{$alias}.conf";

            // Write configuration file using sudo
            $tempFile = tempnam(sys_get_temp_dir(), 'apache_');
            if (!File::put($tempFile, $configContent)) {
                throw new Exception("Failed to write temporary configuration file");
            }

            // Move the file to Apache config directory using sudo
            $command = sprintf('sudo mv %s %s', 
                escapeshellarg($tempFile), 
                escapeshellarg($configPath)
            );
            exec($command, $output, $returnCode);
            if ($returnCode !== 0) {
                throw new Exception("Failed to move Apache configuration file: " . implode("\n", $output));
            }

            // Set proper permissions
            $command = sprintf('sudo chown root:root %s && sudo chmod 644 %s',
                escapeshellarg($configPath),
                escapeshellarg($configPath)
            );
            exec($command, $output, $returnCode);
            if ($returnCode !== 0) {
                throw new Exception("Failed to set Apache configuration file permissions: " . implode("\n", $output));
            }

            // Test Apache configuration
            $output = [];
            $returnCode = 0;
            exec('sudo apachectl -t 2>&1', $output, $returnCode);
            if ($returnCode !== 0) {
                throw new Exception("Apache configuration test failed: " . implode("\n", $output));
            }

            // Reload Apache
            exec('sudo systemctl reload httpd 2>&1', $output, $returnCode);
            if ($returnCode !== 0) {
                throw new Exception("Failed to reload Apache: " . implode("\n", $output));
            }

            Log::info("Apache configuration completed successfully", [
                'alias' => $alias,
                'config_path' => $configPath
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
                $command = sprintf('sudo rm %s', escapeshellarg($configPath));
                exec($command, $output, $returnCode);
                if ($returnCode !== 0) {
                    throw new Exception("Failed to remove Apache configuration file: " . implode("\n", $output));
                }
            }

            // Reload Apache
            exec('sudo systemctl reload httpd 2>&1', $output, $returnCode);
            if ($returnCode !== 0) {
                throw new Exception("Failed to reload Apache after removing configuration: " . implode("\n", $output));
            }

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
