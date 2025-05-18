<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ApacheConfigService
{
    private $configDir;
    private $logDir;
    private $scriptPath;

    public function __construct()
    {
        $this->configDir = '/etc/httpd/conf.d';
        $this->logDir = '/var/log/httpd';
        $this->scriptPath = '/usr/local/bin/manage-apache-config';
    }

    public function configure(string $alias, string $targetPath): void
    {
        $timestamp = now()->toDateTimeString();
        $instanceDomain = "{$alias}.nbcsaccos.co.tz";

        try {
            // Ensure log directory exists
            $this->ensureLogDirectory();

            // Create and execute the configuration script
            $this->createConfigScript($alias, $targetPath);

            // Execute the script with sudo
            $this->executeConfigScript($alias, $targetPath);

            Log::info("[{$timestamp}] Apache configured successfully for '{$instanceDomain}'");
        } catch (Exception $e) {
            Log::error("[{$timestamp}] Apache configuration failed: " . $e->getMessage());
            throw new Exception("Apache configuration failed: " . $e->getMessage());
        }
    }

    private function ensureLogDirectory(): void
    {
        if (!is_dir($this->logDir)) {
            if (!mkdir($this->logDir, 0755, true)) {
                throw new Exception("Failed to create log directory: {$this->logDir}");
            }
        }
    }

    private function createConfigScript(string $alias, string $targetPath): void
    {
        $scriptContent = <<<BASH
#!/bin/bash

# Check if running as root
if [ "\$EUID" -ne 0 ]; then
    echo "Please run as root"
    exit 1
fi

# Create Apache config
cat > {$this->configDir}/{$alias}.conf << EOF
<VirtualHost *:80>
    ServerName {$alias}.nbcsaccos.co.tz
    DocumentRoot "{$targetPath}/public"

    <Directory "{$targetPath}/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog "/var/log/httpd/{$alias}-error.log"
    CustomLog "/var/log/httpd/{$alias}-access.log" combined
</VirtualHost>
EOF

# Set permissions
chmod 644 {$this->configDir}/{$alias}.conf

# Reload Apache
systemctl reload httpd
BASH;

        if (!file_put_contents($this->scriptPath, $scriptContent)) {
            throw new Exception("Failed to create Apache configuration script");
        }

        chmod($this->scriptPath, 0755);
    }

    private function executeConfigScript(string $alias, string $targetPath): void
    {

        $aliasEscaped = escapeshellarg($alias);

        $targetPathEscaped = escapeshellarg($targetPath);




        $command = "sudo /usr/local/bin/manage-apache-config.sh {$aliasEscaped} {$targetPathEscaped }";
        $logFile = '/var/log/manage-apache-config.log';

        // Ensure the log file is writable
        if (!file_exists($logFile)) {
            touch($logFile);
            chmod($logFile, 0666);
        }

        $process = new Process([$command]);
        $process->setTimeout(300); // 5 minutes timeout

        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
            Log::error("Apache config failed: " . $exception->getMessage());
            throw $exception;
        }




       // $cmd = "sudo /usr/local/bin/manage-apache-config {$aliasEscaped} {$targetPathEscaped}";
        // $cmd = "sudo /usr/local/bin/manage-apache-config {$aliasEscaped} {$targetPathEscaped} 2>&1";
        // exec($cmd, $output, $returnCode);

        // if ($returnCode !== 0) {
        //     $errorOutput = implode("\n", $output);
        //     Log::error("Apache config script failed:\nCommand: {$cmd}\nError:\n{$errorOutput}");
        //     throw new Exception("Apache config failed: {$errorOutput}");
        // }
    }

    public function removeConfig(string $alias): void
    {
        $configFile = "{$this->configDir}/{$alias}.conf";

        if (file_exists($configFile)) {
            $cmd = "sudo rm {$configFile}";
            exec($cmd, $output, $returnCode);

            if ($returnCode !== 0) {
                throw new Exception("Failed to remove Apache configuration for {$alias}");
            }

            // Reload Apache
            exec("sudo systemctl reload httpd", $output, $returnCode);
            if ($returnCode !== 0) {
                throw new Exception("Failed to reload Apache after removing configuration");
            }
        }
    }
}
