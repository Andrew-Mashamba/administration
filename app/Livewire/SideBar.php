<?php

namespace App\Livewire;

use App\Models\Menu;
use Livewire\Component;

class SideBar extends Component
{
    public $menu_id = 1;
    public $tab_id = 1;
    public $menuItems = [];

    protected $listeners = ['menuItem' => 'handleMenuItem'];

    public function mount()
    {     
        $this->menuItems = Menu::where('system_id', 1)
            ->orderBy('menu_number')
            ->get(['id', 'menu_name', 'menu_number'])
            ->toArray();
        
        // Get the current route name to determine initial tab
        $routeName = request()->route()->getName();
        if (str_contains($routeName, 'dashboard')) {
            $this->tab_id = 1;
        } elseif (str_contains($routeName, 'users')) {
            $this->tab_id = 2;
        } elseif (str_contains($routeName, 'institutions')) {
            $this->tab_id = 3;
        } elseif (str_contains($routeName, 'settings')) {
            $this->tab_id = 4;
        }
    }

    public function menuItemClicked($item)
    {
        // dd($item);
        $this->tab_id = $item;        
        $this->dispatch('menuItem', $item);
    }

    public function handleMenuItem($item)
    {
        $this->tab_id = $item;
    }

    public function render()
    {
        return view('livewire.side-bar', [
            'menuItems' => $this->menuItems
        ]);
    }
}
