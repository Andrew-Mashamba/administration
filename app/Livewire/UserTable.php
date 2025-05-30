<?php

namespace App\Livewire;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class UserTable extends PowerGridComponent
{
    public string $tableName = 'user-table-zucuvc-table';

    protected $listeners = ['user-updated' => '$refresh'];

    public function setUp(): array
    {
        // $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput()
                ->showToggleColumns()
                ->includeViewOnTop('livewire.users.new-user-button'),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return DB::table('users')
            ->select([
                'users.*',
                DB::raw('CASE 
                    WHEN users.blocked_at IS NOT NULL 
                    THEN \'Blocked\' 
                    ELSE \'Active\' 
                END as status')
            ]);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('email')
            ->add('status')
            ->add('blocked_at')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),

            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'status')
                ->sortable()
                ->searchable()
                ->contentClasses([
                    'Active' => 'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800',
                    'Blocked' => 'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800',
                    'Deleted' => 'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800',
                    'Pending' => 'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800',
                ]),

            // Column::make('Created at', 'created_at_formatted', 'created_at')
            //     ->sortable(),

            Column::action('Action')
        ];
    }

    // public function filters(): array
    // {
    //     return [
    //         Filter::select('status', 'status')
    //             ->dataSource([
    //                 ['label' => 'Active', 'value' => 'Active'],
    //                 ['label' => 'Blocked', 'value' => 'Blocked'],
    //             ])
    //             ->optionValue('value')
    //             ->optionLabel('label'),
    //     ];
    // }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->dispatch('open-modal', $rowId);
    }

    #[\Livewire\Attributes\On('toggle-block')]
    public function toggleBlock($rowId): void
    {
        $user = DB::table('users')->where('id', $rowId)->first();
        
        if ($user->blocked_at) {
            DB::table('users')->where('id', $rowId)->update(['blocked_at' => null]);
        } else {
            DB::table('users')->where('id', $rowId)->update(['blocked_at' => now()]);
        }
    }

    public function actions($row): array
    {
        $isBlocked = !is_null($row->blocked_at);
        
        return [
            Button::add('edit')
                ->slot('<div class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </div>')
                ->id()
                ->class('pg-btn-white')
                ->dispatch('edit', ['rowId' => $row->id]),
                
            Button::add('toggle-block')
                ->slot($isBlocked ? 
                    '<div class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-green-700 bg-green-100 hover:bg-green-200 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                        </svg>
                        Unblock
                    </div>' : 
                    '<div class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-red-700 bg-red-100 hover:bg-red-200 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Block
                    </div>')
                ->id()
                ->class('pg-btn-white')
                ->dispatch('toggle-block', ['rowId' => $row->id])
        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
