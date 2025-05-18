<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

class LogServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Configure daily logging for all channels
        $channels = ['emergency', 'error', 'warning', 'info', 'debug'];

        foreach ($channels as $channel) {
            Log::channel($channel)->setHandlers([
                new RotatingFileHandler(
                    storage_path("logs/{$channel}.log"),
                    30,
                    Logger::DEBUG,
                    true,
                    0664
                )
            ]);
        }

        // Set up custom formatter for better log readability
        $dateFormat = "Y-m-d H:i:s";
        $output = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
        $formatter = new LineFormatter($output, $dateFormat);

        // Apply formatter to all channels
        foreach ($channels as $channel) {
            $handler = Log::channel($channel)->getHandlers()[0];
            $handler->setFormatter($formatter);
        }
    }
}
