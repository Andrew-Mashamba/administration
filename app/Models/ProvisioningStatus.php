<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProvisioningStatus extends Model
{
    protected $fillable = [
        'alias',
        'status',
        'step',
        'message',
        'data',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'data' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
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
            'started_at' => now()
        ]);
    }

    public function markCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
    }

    public function markFailed(string $message): void
    {
        $this->update([
            'status' => 'failed',
            'message' => $message,
            'completed_at' => now()
        ]);
    }

    public function updateProgress(string $step, string $message, array $data = []): void
    {
        $this->update([
            'step' => $step,
            'message' => $message,
            'data' => $data
        ]);
    }
}
