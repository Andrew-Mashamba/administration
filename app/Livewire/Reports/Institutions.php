<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Institution;
use Illuminate\Support\Facades\DB;

class Institutions extends Component
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

    public $pendingInstitutions;

    public function mount()
    {
        $this->totalInstitutions = Institution::count();
        $this->activeInstitutions = Institution::where('status', 'active')->count();
        $this->inactiveInstitutions = Institution::where('status', 'inactive')->count();
        $this->microfinanceInstitutions = Institution::where('institution_type', 'microfinance')->count();
        $this->saccoInstitutions = Institution::where('institution_type', 'saccos')->count();
        $this->activeMicrofinanceInstitutions = Institution::where('institution_type', 'microfinance')->where('status', 'active')->count();
        $this->activeSaccoInstitutions = Institution::where('institution_type', 'saccos')->where('status', 'active')->count();
        $this->inactiveMicrofinanceInstitutions = Institution::where('institution_type', 'microfinance')->where('status', 'inactive')->count();
        $this->inactiveSaccoInstitutions = Institution::where('institution_type', 'saccos')->where('status', 'inactive')->count();



        $this->pendingInstitutions = DB::table('provisioning_statuses')->where('status', 'pending')->count();
    }
    public function render()
    {
        return view('livewire.reports.institutions');
    }
} 