<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class System extends Component
{
    public $menu_id = 1; // Default to dashboard
    public $tab_id = 1;
    public $menuItems = [];
    public $isLoading = false;

    protected $listeners = [
        'menuItem' => 'handleMenuItem',
        'refreshMenu' => 'refreshMenuState'
    ];

    public function mount()
    {
        $this->initializeMenu();
        $this->checkUserStatus();
    }

    protected function initializeMenu()
    {
        // Load menu items from database
        $this->menuItems = Menu::where('system_id', 1)
            ->orderBy('menu_number')
            ->get(['id', 'menu_name', 'menu_title', 'menu_number'])
            ->toArray();

        // Get the current route name to determine initial tab
        $routeName = request()->route()->getName();
        if (str_contains($routeName, 'dashboard')) {
            $this->menu_id = 1;
        } elseif (str_contains($routeName, 'users')) {
            $this->menu_id = 2;
        } elseif (str_contains($routeName, 'institutions')) {
            $this->menu_id = 3;
        }
    }

    protected function checkUserStatus()
    {
        $user = Auth::user();
        if ($user) {
            // Update verification status
            // DB::table('users')->where('id', $user->id)->update(['email_verified_at' => now()]);

            // Get user status
            $userStatus = $user->status;

            // Set menu_id based on user status
            if (in_array($userStatus, ['PENDING', 'BLOCKED', 'DELETED'])) {
                $this->menu_id = 9;
                $this->tab_id = 9;
            }
        }
    }

    public function handleMenuItem($item)
    {
        $this->isLoading = true;
        $this->menu_id = (int)$item;
        $this->tab_id = (int)$item;
        $this->isLoading = false;
    }

    public function refreshMenuState()
    {
        $this->initializeMenu();
        $this->checkUserStatus();
    }

    public function render()
    {
        return view('livewire.system', [
            'menuItems' => $this->menuItems
        ]);
    }
}
