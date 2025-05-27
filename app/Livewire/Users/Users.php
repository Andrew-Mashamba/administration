<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;

class Users extends Component
{
    public $menu_id = 2;
    public $tab_id = 2;
    public $totalUsers = 0;
    public $activeUsers = 0;
    public $inactiveUsers = 0;

    protected $listeners = ['menuItemClicked'];

    public function menuItemClicked($item)
    {
        $this->menu_id = $item;
        $this->tab_id = $item;
    }

    public function mount()
    {
        $this->calculateUserStatistics();
    }

    public function calculateUserStatistics()
    {
        $this->totalUsers = User::count();
        $this->activeUsers = User::where('status', 'ACTIVE')->count();
        $this->inactiveUsers = User::where('status', 'INACTIVE')->count();
    }

    public function render()
    {
        return view('livewire.users.users');
    }
}
