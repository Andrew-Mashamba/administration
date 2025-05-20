<div>
    <div class="bg-white rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Institutions Reports</h2>
        <div class="grid grid-cols-5 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Sample Report Cards -->
            <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <h3 class="text-sm font-medium text-gray-900">Total Institutions</h3>
                <p class="mt-2 text-3xl font-bold text-indigo-600">{{ $totalInstitutions }}</p>
                <span class="text-sm text-yellow-500 mr-4">Microfinance: {{ $microfinanceInstitutions }}</span>
                <span class="text-sm text-blue-500">SACCOs: {{ $saccoInstitutions }}</span>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <h3 class="text-sm font-medium text-gray-900">Active Institutions</h3>
                <p class="mt-2 text-3xl font-bold text-green-600">{{ $activeInstitutions }}</p>
                <span class="text-sm text-yellow-500 mr-4">Microfinance: {{ $activeMicrofinanceInstitutions }}</span>
                <span class="text-sm text-blue-500">SACCOs: {{ $activeSaccoInstitutions }}</span>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <h3 class="text-sm font-medium text-gray-900">Inactive Institutions</h3>
                <p class="mt-2 text-3xl font-bold text-red-600">{{ $inactiveInstitutions }}</p>
                <span class="text-sm text-yellow-500 mr-4">Microfinance: {{ $inactiveMicrofinanceInstitutions }}</span>
                <span class="text-sm text-blue-500">SACCOs: {{ $inactiveSaccoInstitutions }}</span>
            </div>      
        </div>
    </div>
    <div class="">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">                
                <livewire:reports.institutions-table />
                
                <div wire:loading wire:target="exportSelected" class="absolute inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
                    <div class="bg-white p-4 rounded-lg shadow-lg">
                        <div class="flex items-center space-x-3">
                            <svg class="animate-spin h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-gray-700">Exporting selected records...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 