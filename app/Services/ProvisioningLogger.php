<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProvisioningLogger
{
    private string $alias;
    private string $logPath;
    private array $context = [];

    public function __construct(string $alias)
    {
        $this->alias = $alias;
        $this->logPath = "provisioning/{$alias}";
        $this->initializeLog();
    }

    private function initializeLog(): void
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $header = "=== Provisioning Log for {$this->alias} ===\n";
        $header .= "Started at: " . Carbon::now()->toDateTimeString() . "\n";
        $header .= "=====================================\n\n";

        Storage::put("{$this->logPath}/provisioning.log", $header);
    }

    public function logStep(string $step, string $message, array $context = []): void
    {
        $timestamp = Carbon::now()->format('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] [STEP: {$step}] {$message}";

        if (!empty($context)) {
            $logMessage .= "\nContext: " . json_encode($context, JSON_PRETTY_PRINT);
        }

        $logMessage .= "\n";

        // Log to file
        Storage::append("{$this->logPath}/provisioning.log", $logMessage);

        // Log to Laravel's logging system
        Log::info("Provisioning [{$this->alias}] - {$step}", [
            'message' => $message,
            'context' => $context
        ]);
    }

    public function logError(string $step, string $message, \Throwable $exception, array $context = []): void
    {
        $timestamp = Carbon::now()->format('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] [ERROR] [STEP: {$step}] {$message}\n";
        $logMessage .= "Exception: " . get_class($exception) . "\n";
        $logMessage .= "Message: " . $exception->getMessage() . "\n";
        $logMessage .= "File: " . $exception->getFile() . ":" . $exception->getLine() . "\n";
        $logMessage .= "Stack Trace:\n" . $exception->getTraceAsString() . "\n";

        if (!empty($context)) {
            $logMessage .= "Context: " . json_encode($context, JSON_PRETTY_PRINT) . "\n";
        }

        $logMessage .= "\n";

        // Log to file
        Storage::append("{$this->logPath}/error.log", $logMessage);

        // Log to Laravel's logging system
        Log::error("Provisioning Error [{$this->alias}] - {$step}", [
            'message' => $message,
            'exception' => $exception,
            'context' => $context
        ]);
    }

    public function logDebug(string $step, string $message, array $context = []): void
    {
        $timestamp = Carbon::now()->format('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] [DEBUG] [STEP: {$step}] {$message}";

        if (!empty($context)) {
            $logMessage .= "\nContext: " . json_encode($context, JSON_PRETTY_PRINT);
        }

        $logMessage .= "\n";

        // Log to file
        Storage::append("{$this->logPath}/debug.log", $logMessage);

        // Log to Laravel's logging system
        Log::debug("Provisioning Debug [{$this->alias}] - {$step}", [
            'message' => $message,
            'context' => $context
        ]);
    }

    public function logCompletion(bool $success, ?string $message = null): void
    {
        $timestamp = Carbon::now()->format('Y-m-d H:i:s');
        $status = $success ? 'SUCCESS' : 'FAILED';
        $logMessage = "[{$timestamp}] [COMPLETION] Status: {$status}";

        if ($message) {
            $logMessage .= "\nMessage: {$message}";
        }

        $logMessage .= "\n";
        $logMessage .= "=====================================\n\n";

        // Log to file
        Storage::append("{$this->logPath}/provisioning.log", $logMessage);

        // Log to Laravel's logging system
        Log::info("Provisioning Completion [{$this->alias}]", [
            'status' => $status,
            'message' => $message
        ]);
    }

    public function getLogContent(string $type = 'provisioning'): ?string
    {
        $file = "{$this->logPath}/{$type}.log";
        return Storage::exists($file) ? Storage::get($file) : null;
    }

    public function getLogFiles(): array
    {
        return [
            'provisioning' => "{$this->logPath}/provisioning.log",
            'error' => "{$this->logPath}/error.log",
            'debug' => "{$this->logPath}/debug.log"
        ];
    }
}
