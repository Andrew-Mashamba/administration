<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Jetstream;

class Profile extends Component
{
    public $name = '';
    public $email = '';
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $menu_id = 5;
    public $tab_id = 5;

    protected $listeners = ['menuItemClicked'];

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
    }

    public function menuItemClicked($item)
    {
        $this->menu_id = $item;
        $this->tab_id = $item;
    }

    public function updateProfile()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->forceFill([
            'name' => $this->name,
            'email' => $this->email,
        ])->save();

        session()->flash('message', 'Profile updated successfully.');
    }

    public function updatePassword()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $this->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->forceFill([
            'password' => Hash::make($this->new_password),
        ])->save();

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('message', 'Password updated successfully.');
    }

    public function render()
    {
        if (!Auth::check()) {
            return view('livewire.profile.unauthorized');
        }

        return view('livewire.profile.profile');
    }
} 