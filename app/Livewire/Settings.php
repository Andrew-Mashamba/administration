<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

class Settings extends Component
{
    public $system_name;
    public $timezone;
    public $mail_host;
    public $mail_port;

    public function mount()
    {
        $this->system_name = config('app.name');
        $this->timezone = config('app.timezone');
        $this->mail_host = config('mail.mailers.smtp.host');
        $this->mail_port = config('mail.mailers.smtp.port');
    }

    public function save()
    {
        $this->validate([
            'system_name' => 'required|string|max:255',
            'timezone' => 'required|string|max:255',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|integer|min:1|max:65535',
        ]);

        // Update .env file
        $this->updateEnvFile([
            'APP_NAME' => $this->system_name,
            'APP_TIMEZONE' => $this->timezone,
            'MAIL_HOST' => $this->mail_host,
            'MAIL_PORT' => $this->mail_port,
        ]);

        // Clear config cache
        Artisan::call('config:clear');
        Artisan::call('config:cache');

        session()->flash('message', 'Settings saved successfully.');
    }

    protected function updateEnvFile($data)
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}=\"{$value}\"";
            
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }

        file_put_contents($envFile, $envContent);
    }

    public function render()
    {
        return view('livewire.settings');
    }
} 