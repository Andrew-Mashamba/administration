<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProvisioningStatus;

class Dashboard extends Component
{
    public function render()
    {
        $totalInstitutions = ProvisioningStatus::count();
        $pendingRequests = ProvisioningStatus::where('status', 'pending')->count();
        $completedRequests = ProvisioningStatus::where('status', 'completed')->count();
        $failedRequests = ProvisioningStatus::where('status', 'failed')->count();

        return view('livewire.dashboard', [
            'totalInstitutions' => $totalInstitutions,
            'pendingRequests' => $pendingRequests,
            'completedRequests' => $completedRequests,
            'failedRequests' => $failedRequests,
        ]);
    }
} 