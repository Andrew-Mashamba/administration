<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProvisioningStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private string $alias;
    private string $step;
    private string $description;
    private ?string $errorMessage;
    private int $progress;

    public function __construct(string $alias, string $step, string $description, ?string $errorMessage = null, int $progress = 0)
    {
        $this->alias = $alias;
        $this->step = $step;
        $this->description = $description;
        $this->errorMessage = $errorMessage;
        $this->progress = $progress;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject("SACCO Provisioning Status: {$this->alias}")
            ->greeting("Hello!")
            ->line("This is an update regarding the provisioning of {$this->alias}.")
            ->line("Current Progress: {$this->progress}%");

        if ($this->errorMessage) {
            $message->error()
                ->line("An error occurred during the following step:")
                ->line("Step: {$this->description}")
                ->line("Error: {$this->errorMessage}")
                ->action('View Status', url('/provisioning-status'));
        } else {
            $message->line("Current Status:")
                ->line("Step: {$this->description}")
                ->action('View Status', url('/provisioning-status'));
        }

        return $message;
    }
}
