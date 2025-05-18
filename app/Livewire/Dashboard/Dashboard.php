<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class Dashboard extends Component
{
    public $menu_id = 1;
    public $tab_id = 1;
    protected $listeners = ['menuItemClicked'];

    
    public function mount()
    {
        // $this->menu_id = 1;
    }

    public function menuItemClicked($item)
    {
        $this->menu_id = $item;
        $this->tab_id = $item;
        $this->dispatchBrowserEvent('contentChanged');
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard');
    }
}
