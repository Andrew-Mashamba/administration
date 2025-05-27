<?php

namespace App\Livewire\Institutions;

use Livewire\Component;

class Institution extends Component
{
    public $menu_id = 3;
    public $tab_id = 3;

    public $submenu = 'management';

    protected $listeners = ['menuItemClicked'];

    public function menuItemClicked($item)
    {
        $this->menu_id = $item;
        $this->tab_id = $item;
    }

    public function switchSubmenu($submenu)
    {
        $this->submenu = $submenu;
    }

    public function mount()
    {
        // $this->menu_id = 2;
    }

    public function render()
    {
        return view('livewire.institutions.institution');
    }
}
