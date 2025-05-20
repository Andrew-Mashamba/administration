<?php

namespace App\Jobs;

use App\Models\ProvisioningStatus;
use App\Services\SaccoProvisioner;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class ProvisionSaccoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600; // 10 minutes
    public $tries = 1;

    private string $alias;
    private string $dbName;
    private string $dbHost;
    private string $dbUser;
    private string $dbPassword;
    private ?string $managerEmail;
    private ?string $itEmail;

    public function __construct(
        string $alias,
        string $dbName,
        string $dbHost,
        string $dbUser,
        string $dbPassword,
        ?string $managerEmail = null,
        ?string $itEmail = null
    ) {
        $this->alias = $alias;
        $this->dbName = $dbName;
        $this->dbHost = $dbHost;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->managerEmail = $managerEmail;
        $this->itEmail = $itEmail;
    }

    public function handle()
    {
        // Check for ongoing provisioning
        if (ProvisioningStatus::isProvisioningInProgress()) {
            $ongoing = ProvisioningStatus::where('status', 'in_progress')
                ->whereNull('completed_at')
                ->first();

            Log::warning("Concurrent provisioning attempt blocked", [
                'requested_alias' => $this->alias,
                'ongoing_alias' => $ongoing->alias,
                'ongoing_step' => $ongoing->step
            ]);

            throw new \Exception(
                "Another provisioning process is already in progress for {$ongoing->alias}. " .
                "Please wait for it to complete before starting a new one."
            );
        }

        $status = ProvisioningStatus::create([
            'alias' => $this->alias,
            'status' => 'in_progress',
            'started_at' => now(),
            'db_name' => $this->dbName,
            'db_host' => $this->dbHost,
            'db_user' => $this->dbUser,
            'db_password' => $this->dbPassword,
            'manager_email' => $this->managerEmail,
            'it_email' => $this->itEmail
        ]);

        try {
            $provisioner = new SaccoProvisioner();
            $provisioner->setProgressCallback(function ($step, $message, $data) use ($status) {
                $status->updateProgress($step, $message, $data);
            });

            $result = $provisioner->provision(
                $this->alias,
                $this->dbName,
                $this->dbHost,
                $this->dbUser,
                $this->dbPassword,
                $this->managerEmail,
                $this->itEmail
            );

            $status->markCompleted();

        } catch (\Exception $e) {
            Log::error("Provisioning failed", [
                'alias' => $this->alias,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $status->markFailed($e->getMessage());
            throw $e;
        }
    }
}
