<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ProvisioningStatus extends Model
{
    protected $fillable = [
        'alias',
        'status',
        'step',
        'message',
        'data',
        'db_name',
        'db_host',
        'db_user',
        'db_password',
        'manager_email',
        'it_email',
        'started_at',
        'completed_at',
        'progress',
        'error_details'
    ];

    protected $casts = [
        'data' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'error_details' => 'array'
    ];

    public static function isProvisioningInProgress(): bool
    {
        return static::where('status', 'in_progress')
            ->whereNull('completed_at')
            ->exists();
    }

    public static function markStarted(string $alias): self
    {
        return static::create([
            'alias' => $alias,
            'status' => 'in_progress',
            'started_at' => now(),
            'progress' => 0
        ]);
    }

    public function markCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'progress' => 100,
            'completed_at' => now()
        ]);

        Log::info("Provisioning completed successfully", [
            'alias' => $this->alias,
            'duration' => $this->started_at->diffInSeconds(now())
        ]);
    }

    public function markFailed(string $message, array $errorDetails = []): void
    {
        $this->update([
            'status' => 'failed',
            'message' => $message,
            'error_details' => $errorDetails,
            'completed_at' => now()
        ]);

        Log::error("Provisioning failed", [
            'alias' => $this->alias,
            'message' => $message,
            'error_details' => $errorDetails,
            'duration' => $this->started_at->diffInSeconds(now())
        ]);
    }

    public function updateProgress(string $step, string $message, array $data = []): void
    {
        $progress = $this->calculateProgress($step);
        
        $this->update([
            'step' => $step,
            'message' => $message,
            'data' => $data,
            'progress' => $progress
        ]);

        Log::info("Provisioning progress updated", [
            'alias' => $this->alias,
            'step' => $step,
            'progress' => $progress,
            'message' => $message
        ]);
    }

    private function calculateProgress(string $step): int
    {
        $steps = [
            'started' => 0,
            'cloning' => 10,
            'database' => 20,
            'env' => 30,
            'livewire' => 40,
            'apache' => 50,
            'post_install' => 60,
            'users' => 70,
            'finalizing' => 90,
            'completed' => 100
        ];

        return $steps[$step] ?? 0;
    }

    public function getDurationAttribute(): string
    {
        if (!$this->started_at) {
            return 'Not started';
        }

        $end = $this->completed_at ?? now();
        $duration = $this->started_at->diffInSeconds($end);

        if ($duration < 60) {
            return $duration . ' seconds';
        } elseif ($duration < 3600) {
            return round($duration / 60) . ' minutes';
        } else {
            return round($duration / 3600, 1) . ' hours';
        }
    }
}
