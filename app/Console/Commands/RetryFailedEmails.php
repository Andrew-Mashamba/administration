<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class RetryFailedEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:retry {--max-attempts=3} {--older-than=24}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry sending failed emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $maxAttempts = $this->option('max-attempts');
        $olderThan = $this->option('older-than');

        $failedEmails = DB::table('failed_emails')
            ->where('attempts', '<', $maxAttempts)
            ->where('created_at', '<=', now()->subHours($olderThan))
            ->get();

        $this->info("Found {$failedEmails->count()} failed emails to retry.");

        foreach ($failedEmails as $failedEmail) {
            try {
                $data = json_decode($failedEmail->data, true);

                Mail::queue($failedEmail->template, $data, function($message) use ($failedEmail) {
                    $message->to($failedEmail->email)
                           ->subject('Welcome to Your New SACCO Instance');
                });

                // Delete the failed email record on success
                DB::table('failed_emails')->where('id', $failedEmail->id)->delete();

                $this->info("Successfully queued email for {$failedEmail->email}");
            } catch (\Exception $e) {
                // Update attempt count and last attempt time
                DB::table('failed_emails')
                    ->where('id', $failedEmail->id)
                    ->update([
                        'attempts' => $failedEmail->attempts + 1,
                        'last_attempt_at' => now(),
                        'error' => $e->getMessage()
                    ]);

                $this->error("Failed to retry email for {$failedEmail->email}: {$e->getMessage()}");
                Log::error("Failed to retry email", [
                    'email' => $failedEmail->email,
                    'error' => $e->getMessage(),
                    'attempts' => $failedEmail->attempts + 1
                ]);
            }
        }

        $this->info('Email retry process completed.');
    }
}
