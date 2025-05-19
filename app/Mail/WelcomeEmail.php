<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $password;
    public $url;
    public $name;

    public function __construct(array $data)
    {
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->url = $data['url'];
        $this->name = $data['name'];
    }

    public function build()
    {
        return $this->view('emails.welcome')
                    ->subject('Welcome to Your New SACCO Instance')
                    ->with([
                        'email' => $this->email,
                        'password' => $this->password,
                        'url' => $this->url,
                        'name' => $this->name
                    ]);
    }
} 