<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ProvisioningProgress extends Notification implements ShouldQueue
{
    use Queueable;

    private $step;
    private $message;
    private $data;

    public function __construct($step, $message, $data = [])
    {
        $this->step = $step;
        $this->message = $message;
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'step' => $this->step,
            'message' => $this->message,
            'data' => $this->data,
            'timestamp' => now()->toIso8601String(),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'step' => $this->step,
            'message' => $this->message,
            'data' => $this->data,
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
