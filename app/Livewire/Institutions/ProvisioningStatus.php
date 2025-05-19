<?php

namespace App\Livewire\Institutions;

use Livewire\Component;
use App\Models\ProvisioningStatus as ProvisioningStatusModel;
use App\Jobs\ProvisionSaccoJob;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ProvisioningStatusNotification;
use App\Services\ProvisioningLogger;

class ProvisioningStatus extends Component
{
    public $search = '';
    public $statusFilter = '';
    public $showErrorDetails = null;
    public $showLogs = null;

    // Define detailed provisioning steps and their progress percentages
    protected $provisioningSteps = [
        'initializing' => [
            'progress' => 0,
            'description' => 'Initializing provisioning process',
            'error_message' => 'Failed to initialize provisioning process'
        ],
        'validating_credentials' => [
            'progress' => 5,
            'description' => 'Validating database credentials',
            'error_message' => 'Invalid database credentials'
        ],
        'creating_database' => [
            'progress' => 10,
            'description' => 'Creating database',
            'error_message' => 'Failed to create database'
        ],
        'creating_schema' => [
            'progress' => 20,
            'description' => 'Creating database schema',
            'error_message' => 'Failed to create database schema'
        ],
        'setting_up_core_tables' => [
            'progress' => 30,
            'description' => 'Setting up core tables',
            'error_message' => 'Failed to set up core tables'
        ],
        'setting_up_auxiliary_tables' => [
            'progress' => 40,
            'description' => 'Setting up auxiliary tables',
            'error_message' => 'Failed to set up auxiliary tables'
        ],
        'configuring_system_settings' => [
            'progress' => 50,
            'description' => 'Configuring system settings',
            'error_message' => 'Failed to configure system settings'
        ],
        'setting_up_permissions' => [
            'progress' => 60,
            'description' => 'Setting up user permissions',
            'error_message' => 'Failed to set up user permissions'
        ],
        'creating_admin_user' => [
            'progress' => 70,
            'description' => 'Creating administrator account',
            'error_message' => 'Failed to create administrator account'
        ],
        'creating_manager_user' => [
            'progress' => 80,
            'description' => 'Creating manager account',
            'error_message' => 'Failed to create manager account'
        ],
        'sending_setup_notifications' => [
            'progress' => 90,
            'description' => 'Sending setup notifications',
            'error_message' => 'Failed to send setup notifications'
        ],
        'finalizing_setup' => [
            'progress' => 95,
            'description' => 'Finalizing setup',
            'error_message' => 'Failed to finalize setup'
        ],
        'completed' => [
            'progress' => 100,
            'description' => 'Setup completed successfully',
            'error_message' => null
        ]
    ];

    public function getTotalPendingProperty()
    {
        return ProvisioningStatusModel::where('status', 'pending')->count();
    }

    public function getTotalInProgressProperty()
    {
        return ProvisioningStatusModel::where('status', 'in_progress')->count();
    }

    public function getTotalCompletedProperty()
    {
        return ProvisioningStatusModel::where('status', 'completed')->count();
    }

    public function getTotalFailedProperty()
    {
        return ProvisioningStatusModel::where('status', 'failed')->count();
    }

    public function getStatusesProperty()
    {
        $query = ProvisioningStatusModel::query();

        if ($this->search) {
            $query->where('alias', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return $query->latest()->paginate(10);
    }

    public function getStepProgress($step)
    {
        return $this->provisioningSteps[$step]['progress'] ?? 0;
    }

    public function getStepDescription($step)
    {
        return $this->provisioningSteps[$step]['description'] ?? 'Unknown step';
    }

    public function getStepErrorMessage($step)
    {
        return $this->provisioningSteps[$step]['error_message'] ?? 'An unknown error occurred';
    }

    public function getNextStep($currentStep)
    {
        $steps = array_keys($this->provisioningSteps);
        $currentIndex = array_search($currentStep, $steps);
        return $steps[$currentIndex + 1] ?? null;
    }

    public function getEstimatedTimeRemaining($status)
    {
        if ($status->status !== 'in_progress' || !$status->started_at) {
            return null;
        }

        $elapsedTime = now()->diffInSeconds($status->started_at);
        $currentProgress = $status->progress;

        if ($currentProgress <= 0) {
            return 'Calculating...';
        }

        $estimatedTotalTime = ($elapsedTime / $currentProgress) * 100;
        $remainingTime = $estimatedTotalTime - $elapsedTime;

        if ($remainingTime < 60) {
            return round($remainingTime) . ' seconds';
        } elseif ($remainingTime < 3600) {
            return round($remainingTime / 60) . ' minutes';
        } else {
            return round($remainingTime / 3600, 1) . ' hours';
        }
    }

    public function getLogs($id)
    {
        $status = ProvisioningStatusModel::find($id);
        if ($status) {
            $logger = new ProvisioningLogger($status->alias);
            $this->showLogs = [
                'provisioning' => $logger->getLogContent('provisioning'),
                'error' => $logger->getLogContent('error'),
                'debug' => $logger->getLogContent('debug')
            ];
        }
    }

    public function retryProvisioning($id)
    {
        $status = ProvisioningStatusModel::find($id);
        if ($status) {
            $logger = new ProvisioningLogger($status->alias);

            $logger->logStep('retry', 'Initiating retry of provisioning process', [
                'previous_status' => $status->status,
                'previous_step' => $status->step
            ]);

            $status->update([
                'status' => 'pending',
                'progress' => 0,
                'step' => 'initializing',
                'message' => 'Retrying provisioning...',
                'data' => null,
                'started_at' => now(),
                'completed_at' => null
            ]);

            // Send retry notification
            $this->sendStepNotification($status, 'initializing');

            // Dispatch the provisioning job again
            ProvisionSaccoJob::dispatch(
                $status->alias,
                $status->db_name,
                $status->db_host,
                $status->db_user,
                $status->db_password,
                $status->manager_email,
                $status->it_email
            );

            $logger->logStep('retry', 'Provisioning job dispatched for retry');
        }
    }

    public function sendStepNotification($status, $step, $isError = false)
    {
        if (!$status->manager_email && !$status->it_email) {
            return;
        }

        $logger = new ProvisioningLogger($status->alias);
        $recipients = array_filter([$status->manager_email, $status->it_email]);

        $logger->logStep('notification', 'Sending step notification', [
            'step' => $step,
            'is_error' => $isError,
            'recipients' => $recipients
        ]);

        foreach ($recipients as $email) {
            Notification::route('mail', $email)
                ->notify(new ProvisioningStatusNotification(
                    $status->alias,
                    $step,
                    $this->getStepDescription($step),
                    $isError ? $this->getStepErrorMessage($step) : null
                ));
        }

        $logger->logStep('notification', 'Step notification sent successfully');
    }

    public function render()
    {
        return view('livewire.institutions.provisioning-status', [
            'totalPending' => $this->totalPending,
            'totalInProgress' => $this->totalInProgress,
            'totalCompleted' => $this->totalCompleted,
            'totalFailed' => $this->totalFailed,
            'statuses' => $this->statuses,
            'provisioningSteps' => $this->provisioningSteps,
            'logs' => $this->showLogs
        ]);
    }
}
