<?php

namespace App\Livewire\Users;

use Livewire\Component;

class Users extends Component
{
    public $menu_id = 2;
    public $tab_id = 2;

    protected $listeners = ['menuItemClicked'];

    public function menuItemClicked($item)
    {
        $this->menu_id = $item;
        $this->tab_id = $item;
    }

    public function mount()
    {
        // $this->menu_id = 1;
    }

    public function render()
    {
        return view('livewire.users.users');
    }
}
