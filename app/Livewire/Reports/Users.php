<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\User;

class Users extends Component
{
    public $activeUsers;
    public $inactiveUsers;

    public $totalUsers;

    public $pendingUsers;

    public function mount()
    {
        $this->activeUsers = User::where('status', 'ACTIVE')->count();
        $this->inactiveUsers = User::where('status', 'INACTIVE')->count();
        $this->pendingUsers = User::where('status', 'PENDING')->count();
        $this->totalUsers = User::count();
    }


    public function render()
    {
        return view('livewire.reports.users');
    }
} 