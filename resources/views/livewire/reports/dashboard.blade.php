<div>
    <div class="bg-white shadow-sm rounded-lg">
        <!-- Submenu Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-4" aria-label="Tabs">
                <button wire:click="switchSubmenu('institutions')" 
                    class="py-4 px-1 border-b-2 font-medium text-sm {{ $submenu === 'institutions' ? 'border-gray-500 text-gray-600 bg-gray-100' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Institutions Reports
                </button>
                <button wire:click="switchSubmenu('users')"
                    class="py-4 px-1 border-b-2 font-medium text-sm {{ $submenu === 'users' ? 'border-gray-500 text-gray-600 bg-gray-100' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Users Reports
                </button>
            </nav>
        </div>

        <!-- Content Area -->
        <div class="p-4">
            @switch($submenu)
                @case('institutions')
                    <livewire:reports.institutions />
                    @break
                @case('users')
                    <livewire:reports.users />
                    @break
                @default
                    <livewire:reports.institutions />
            @endswitch
        </div>
    </div>
</div>
