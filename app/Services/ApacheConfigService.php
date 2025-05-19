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
        $this->apacheConfigDir = '/etc/apache2/sites-available';
        $this->apacheSitesDir = '/etc/apache2/sites-enabled';
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

            if (!File::exists($this->apacheSitesDir)) {
                throw new Exception("Apache sites directory does not exist: {$this->apacheSitesDir}");
            }

            // Create Apache configuration
            $configContent = $this->generateApacheConfig($alias, $targetPath);
            $configPath = "{$this->apacheConfigDir}/{$alias}.conf";

            // Write configuration file
            if (!File::put($configPath, $configContent)) {
                throw new Exception("Failed to write Apache configuration file");
            }

            // Create symbolic link in sites-enabled
            $enabledPath = "{$this->apacheSitesDir}/{$alias}.conf";
            if (File::exists($enabledPath)) {
                File::delete($enabledPath);
            }

            if (!symlink($configPath, $enabledPath)) {
                throw new Exception("Failed to create symbolic link for site configuration");
            }

            // Test Apache configuration
            $output = [];
            $returnCode = 0;
            exec('apache2ctl -t 2>&1', $output, $returnCode);
            if ($returnCode !== 0) {
                throw new Exception("Apache configuration test failed: " . implode("\n", $output));
            }

            // Reload Apache
            exec('systemctl reload apache2 2>&1', $output, $returnCode);
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
            $enabledPath = "{$this->apacheSitesDir}/{$alias}.conf";

            // Remove symbolic link
            if (File::exists($enabledPath)) {
                File::delete($enabledPath);
            }

            // Remove configuration file
            if (File::exists($configPath)) {
                File::delete($configPath);
            }

            // Reload Apache
            exec('systemctl reload apache2', $output, $returnCode);
            if ($returnCode !== 0) {
                throw new Exception("Failed to reload Apache after removing configuration");
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
