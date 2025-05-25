<?php

namespace App\Livewire\Reports;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Rappasoft\LaravelLivewireTables\Views\BulkAction;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UsersTable extends DataTableComponent
{
    protected $model = User::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setBulkActionsThAttributes([
            'class' => 'bg-blue-500',
            'default' => true //make false and add custom color
        ]);
      
        $this->setBulkActionsMenuAttributes([
            'class' => 'bg-green-500',
            'default-colors' => true,
            'default-styling' => true,
        ]);
        $this->setBulkActions([
            'exportSelected' => 'Export',
        ]);
    }

    public function columns(): array
    {
        return [

            
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),
            Column::make('Status', 'status')
                ->sortable()
                ->format(fn($value) => view('livewire.components.status-badge', ['status' => $value])),
            Column::make('Created At', 'created_at')
                ->sortable()
                ->format(fn($value) => $value->format('Y-m-d H:i:s')),
        ];
    }

    public function builder(): Builder
    {
        return User::query()
            ->select([
                'id',
                'name',
                'email',
                'blocked_at',
                'created_at'
            ]);
    }
    
    public function bulkActions(): array
    {
        return [
            'exportSelected' => 'Export',
        ];
    }

    public function exportSelected()
    {
        $users = User::whereIn('id', $this->getSelected())->get();
        return Excel::download(new UsersExport($users), 'Users.xlsx');
    }
} 