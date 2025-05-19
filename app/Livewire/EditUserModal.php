<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            DB::table('users')->insert([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
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

    public function render()
    {
        return view('livewire.edit-user-modal');
    }
}
