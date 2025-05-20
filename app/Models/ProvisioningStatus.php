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
        'progress',
        'error_details',
        'started_at',
        'completed_at',
        'db_name',
        'db_host',
        'db_user',
        'db_password',
        'manager_email',
        'it_email'
    ];

    protected $casts = [
        'data' => 'array',
        'error_details' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress' => 'integer'
    ];

    public static function isProvisioningInProgress(): bool
    {
        return self::where('status', 'in_progress')
            ->whereNull('completed_at')
            ->exists();
    }

    public function updateProgress($step, $message, $data = null)
    {
        $this->update([
            'step' => $step,
            'message' => $message,
            'data' => $data,
            'progress' => $this->calculateProgress($step)
        ]);

        Log::info("Provisioning progress updated", [
            'alias' => $this->alias,
            'step' => $step,
            'message' => $message,
            'progress' => $this->progress
        ]);
    }

    public function markCompleted()
    {
        $this->update([
            'status' => 'completed',
            'progress' => 100,
            'completed_at' => now()
        ]);

        Log::info("Provisioning completed", [
            'alias' => $this->alias,
            'completed_at' => $this->completed_at
        ]);
    }

    public function markFailed($errorMessage, array $errorDetails = [])
    {
        $this->update([
            'status' => 'failed',
            'message' => "Provisioning failed: {$errorMessage}",
            'error_details' => array_merge($errorDetails, [
                'error' => $errorMessage,
                'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
            ]),
            'completed_at' => now()
        ]);

        Log::error("Provisioning failed", [
            'alias' => $this->alias,
            'error' => $errorMessage,
            'details' => $errorDetails
        ]);
    }

    protected function calculateProgress($step)
    {
        $steps = [
            'initializing' => 0,
            'validating_credentials' => 5,
            'creating_database' => 10,
            'creating_schema' => 20,
            'setting_up_core_tables' => 30,
            'setting_up_auxiliary_tables' => 40,
            'configuring_system_settings' => 50,
            'setting_up_permissions' => 60,
            'creating_admin_user' => 70,
            'creating_manager_user' => 80,
            'sending_setup_notifications' => 90,
            'finalizing_setup' => 95,
            'completed' => 100
        ];

        return $steps[$step] ?? 0;
    }

    public function getDurationAttribute()
    {
        if (!$this->started_at) {
            return null;
        }

        $end = $this->completed_at ?? now();
        return $this->started_at->diffInSeconds($end);
    }
}
