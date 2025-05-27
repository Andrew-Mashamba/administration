<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EditUserModal extends Component
{
    public $showModal = false;
    public $userId;
    public $name;
    public $email;
    public $password;
    public $isNewUser = false;

    protected $listeners = ['open-modal' => 'openModal'];

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        // 'password' => 'required_if:isNewUser,true|min:8',
    ];

    public function openModal($userId = null)
    {
        $this->isNewUser = is_null($userId);
        
        if (!$this->isNewUser) {
            $this->userId = $userId;
            $user = DB::table('users')->where('id', $this->userId)->first();
            $this->name = $user->name;
            $this->email = $user->email;
        } else {
            $this->reset(['name', 'email']);
        }
        
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['name', 'email', 'userId', 'isNewUser']);
    }

    public function save()
    {
        $this->validate();

        if ($this->isNewUser) {

            $password = Str::random(8); // Generate a random password

            DB::table('users')->insert([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($password),
                'status' => 'ACTIVE',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            //send email to user
            $this->sendEmail($this->name, $this->email, $password);

            session()->flash('success', 'User created successfully, and credentials sent via email!');

            $this->reset(['name', 'email']);
            $this->showModal = false;

        } else {
            DB::table('users')
                ->where('id', $this->userId)
                ->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'updated_at' => now(),
                ]);
        }

        $this->closeModal();
        $this->dispatch('user-updated');
    }


    public function sendEmail($name, $email, $password)
    {
        $url = config('app.url');
        Mail::send('emails.user-credentials', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'url' => $url,
        ], function ($message) use ($email, $name) {
            $message->to($email, $name)
                    ->subject('Your Login Credentials')
                    ->from('no-reply@nbc.co.tz');
        });
    }

    public function render()
    {
        return view('livewire.edit-user-modal');
    }
}
