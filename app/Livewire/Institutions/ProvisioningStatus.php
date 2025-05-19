<?php

namespace App\Livewire\Institutions;

use Livewire\Component;
use App\Models\ProvisioningStatus as ProvisioningStatusModel;

class ProvisioningStatus extends Component
{    
    public $search = '';
    public $statusFilter = '';
    
    public function getTotalPendingProperty()
    {
        return ProvisioningStatusModel::where('status', 'pending')->count();
    }

    public function getTotalInProgressProperty()
    {
        return ProvisioningStatusModel::where('status', 'in_progress')->count();
    }

    public function getTotalCompletedProperty()
    {
        return ProvisioningStatusModel::where('status', 'completed')->count();
    }

    public function getTotalFailedProperty()
    {
        return ProvisioningStatusModel::where('status', 'failed')->count();
    }

    public function getStatusesProperty()
    {
        $query = ProvisioningStatusModel::query();

        if ($this->search) {
            $query->where('alias', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return $query->latest()->paginate(10);
    }

    public function retryProvisioning($id)
    {
        $status = ProvisioningStatusModel::find($id);
        if ($status) {
            $status->update([
                'status' => 'pending',
                'progress' => 0
            ]);
        }
    }

    public function render()
    {
        return view('livewire.institutions.provisioning-status', [
            'totalPending' => $this->totalPending,
            'totalInProgress' => $this->totalInProgress,
            'totalCompleted' => $this->totalCompleted,
            'totalFailed' => $this->totalFailed,
            'statuses' => $this->statuses
        ]);
    }
}
