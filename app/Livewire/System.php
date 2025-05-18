<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class System extends Component
{
    public $menu_id = 0;
    public $tab_id = 0;

    protected $listeners = ['menuItem' => 'handleMenuItem'];


    public function mount()
    {
        $user = Auth::user();
        if ($user) {
            // Update verification status
            $user->update(['verification_status' => 0]);

            // Get user status
            $userStatus = $user->status;

            // Set menu_id based on user status
            if (in_array($userStatus, ['PENDING', 'BLOCKED', 'DELETED'])) {
                $this->menu_id = 9;
                $this->tab_id = 9;
            } else {
                $this->menu_id = 1;
                $this->tab_id = 1;
            }
        }
    }

    public function handleMenuItem($item)
    {
        $this->menu_id = $item;
        $this->tab_id = $item;
    }

    public function render()
    {
        return view('livewire.system');
    }
}
