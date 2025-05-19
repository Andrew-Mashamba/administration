<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Institution;
class Dashboard extends Component
{
    public $totalInstitutions;
    public $activeInstitutions;
    public $inactiveInstitutions;
    public $microfinanceInstitutions;
    public $saccoInstitutions;
    public $activeMicrofinanceInstitutions;
    public $activeSaccoInstitutions;
    public $inactiveMicrofinanceInstitutions;
    public $inactiveSaccoInstitutions;
    public $data;
    public $menu_id = 1;
    public $tab_id = 1;
    protected $listeners = ['menuItemClicked'];


    
   
       public function mount()
    {
        $this->data = [
            'totalInstitutions' => Institution::count(),
            'activeInstitutions' => Institution::where('status', 'active')->count(),
            'inactiveInstitutions' => Institution::where('status', 'inactive')->count(),
            'microfinanceInstitutions' => Institution::where('institution_type', 'microfinance')->count(),
            'saccoInstitutions' => Institution::where('institution_type', 'saccos')->count(),
            'activeMicrofinanceInstitutions' => Institution::where('institution_type', 'microfinance')->where('status', 'active')->count(),
            'activeSaccoInstitutions' => Institution::where('institution_type', 'saccos')->where('status', 'active')->count(),
            'inactiveMicrofinanceInstitutions' => Institution::where('institution_type', 'microfinance')->where('status', 'inactive')->count(),
            'inactiveSaccoInstitutions' => Institution::where('institution_type', 'saccos')->where('status', 'inactive')->count(),
        ];    
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
