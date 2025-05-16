<?php

namespace App\Livewire\Reports;

use Livewire\Component;

class Dashboard extends Component
{
    public $submenu = 'institutions'; // Default submenu

    public function switchSubmenu($submenu)
    {
        $this->submenu = $submenu;
    }

    public function render()
    {
        return view('livewire.reports.dashboard');
    }
}
