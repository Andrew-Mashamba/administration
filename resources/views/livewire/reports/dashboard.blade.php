<div>
    <div class="bg-white shadow-sm rounded-lg max-w-5xl mx-auto">
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
        <div class="">
            <div wire:loading class="w-full p-2">
                <div class="flex items-center justify-center min-h-[calc(100vh-4rem)] bg-white w-full rounded-md">
                    <div class="text-center m-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="blue" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="blue" class="w-16 h-16 animate-spin">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        </svg>
                        <p class="mt-2 text-lg text-blue-800">Loading...</p>
                    </div>
                </div>
            </div>
            <div wire:loading.remove wire:target="switchSubmenu">
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
</div>
