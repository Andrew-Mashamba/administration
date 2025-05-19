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
        $this->scriptPath = storage_path('app/apache-config/manage-apache-config.sh');
    }

    public function configure(string $alias, string $targetPath): void
    {
        $timestamp = now()->toDateTimeString();
        $instanceDomain = "{$alias}.nbcsaccos.co.tz";

        try {
            // Ensure log directory exists
            $this->ensureLogDirectory();

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

    private function executeConfigScript(string $alias, string $targetPath): void
    {
        $aliasEscaped = escapeshellarg($alias);
        $targetPathEscaped = escapeshellarg($targetPath);

        $command = ['sudo', '/usr/local/bin/manage-apache-config.sh', $aliasEscaped, $targetPathEscaped];
        $process = Process::fromShellCommandline(implode(' ', $command));
        $process->setTimeout(300); // 5 minutes timeout

        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
            Log::error("Apache config failed: " . $exception->getMessage());
            throw $exception;
        }
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
