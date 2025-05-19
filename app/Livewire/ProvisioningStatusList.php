<?php

namespace App\Livewire;

use App\Models\ProvisioningStatus;
use Livewire\Component;
use Livewire\WithPagination;

class ProvisioningStatusList extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.provisioning-status-list', [
            'statuses' => ProvisioningStatus::latest()
                ->paginate(10)
        ]);
    }

    public function getStatusColor($status)
    {
        return match($status) {
            'in_progress' => 'blue',
            'completed' => 'green',
            'failed' => 'red',
            default => 'gray'
        };
    }

    public function getStatusIcon($status)
    {
        return match($status) {
            'in_progress' => 'heroicon-o-clock',
            'completed' => 'heroicon-o-check-circle',
            'failed' => 'heroicon-o-x-circle',
            default => 'heroicon-o-question-mark-circle'
        };
    }
}
