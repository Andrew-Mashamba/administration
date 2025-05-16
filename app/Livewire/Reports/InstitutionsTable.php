<?php

namespace App\Livewire\Reports;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Institution;
use Rappasoft\LaravelLivewireTables\Views\BulkAction;
use App\Exports\InstitutionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
class InstitutionsTable extends DataTableComponent
{
    protected $model = Institution::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setBulkActionsThAttributes([
            'class' => 'bg-blue-500',
            'default' => true
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
            Column::make('Location', 'location')
                ->sortable()
                ->searchable(),
            Column::make('Contact Person', 'contact_person')
                ->sortable()
                ->searchable(),
            Column::make('Phone', 'phone')
                ->sortable()
                ->searchable(),
            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),
            Column::make('Institution Type', 'institution_type')
                ->sortable()
                ->searchable(),
            Column::make('Status', 'status')
                ->sortable()
                ->searchable(),
            Column::make('Created At', 'created_at')
                ->sortable()
                ->format(fn($value) => $value->format('Y-m-d H:i:s')),
        ];
    }

    public function builder(): Builder
    {
        return Institution::query()
            ->select([
                'id',
                'name',
                'location',
                'contact_person',
                'phone',
                'email',
                'institution_type',
                'status',
                'alias',
                'db_name',
                'institution_id',
                'manager_email',
                'manager_phone_number',
                'it_email',
                'it_phone_number',
                'created_at',
                'updated_at'
            ]);
    }

    public function bulkActions(): array
    {
        return [
            'exportSelected' => 'Export',
        ];
    }

public function filters(): array
{
    return [
        SelectFilter::make('Institution Type')
            ->options([
                '' => 'Any',
                'saccos' => 'SACCOS',
                'microfinance' => 'Microfinance',
            ])
            ->filter(function(Builder $builder, string $value) {
                $builder->where('institution_type', $value);
            }),

        SelectFilter::make('Status')
            ->options([
                '' => 'Any',
                'active' => 'Active',
                'inactive' => 'Inactive',
            ])
            ->filter(function(Builder $builder, string $value) {
                $builder->where('status', $value);
            }),
    ];
}



    public function exportSelected()
    {
        $institutions = Institution::whereIn('id', $this->getSelected())->get();
        return Excel::download(new InstitutionsExport($institutions), 'Institutions.xlsx');
    }
    
}