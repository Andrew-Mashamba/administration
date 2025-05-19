<?php

namespace App\Console\Commands;

use App\Models\ProvisioningStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CleanupProvisioningRecords extends Command
{
    protected $signature = 'provisioning:cleanup 
                            {--days=30 : Number of days to keep records}
                            {--dry-run : Show what would be deleted without actually deleting}';

    protected $description = 'Clean up old provisioning records';

    public function handle()
    {
        $days = $this->option('days');
        $dryRun = $this->option('dry-run');
        $cutoffDate = Carbon::now()->subDays($days);

        $query = ProvisioningStatus::where(function ($query) use ($cutoffDate) {
            $query->where('created_at', '<', $cutoffDate)
                  ->where(function ($q) {
                      $q->where('status', 'completed')
                        ->orWhere('status', 'failed');
                  });
        });

        $count = $query->count();

        if ($count === 0) {
            $this->info('No records found to clean up.');
            return;
        }

        if ($dryRun) {
            $this->info("Would delete {$count} records older than {$days} days.");
            $this->table(
                ['ID', 'Alias', 'Status', 'Created At'],
                $query->get(['id', 'alias', 'status', 'created_at'])
                    ->map(fn($record) => [
                        $record->id,
                        $record->alias,
                        $record->status,
                        $record->created_at->format('Y-m-d H:i:s')
                    ])
            );
            return;
        }

        $this->info("Deleting {$count} records older than {$days} days...");

        try {
            $deleted = $query->delete();
            Log::info("Cleaned up {$deleted} old provisioning records");
            $this->info("Successfully deleted {$deleted} records.");
        } catch (\Exception $e) {
            Log::error("Failed to clean up provisioning records: " . $e->getMessage());
            $this->error("Failed to delete records: " . $e->getMessage());
        }
    }
} 