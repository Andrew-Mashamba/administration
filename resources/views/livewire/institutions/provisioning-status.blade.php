<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center space-x-4">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-gray-600 text-sm">Pending Provisioning</h2>
                    <p class="text-2xl font-semibold text-gray-800">{{ $totalPending }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center space-x-4">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-gray-600 text-sm">In Progress Provisioning</h2>
                    <p class="text-2xl font-semibold text-gray-800">{{ $totalInProgress }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center space-x-4">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-gray-600 text-sm">Completed Provisioning</h2>
                    <p class="text-2xl font-semibold text-gray-800">{{ $totalCompleted }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center space-x-4">
                <div class="p-3 rounded-full bg-red-100 text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-gray-600 text-sm">Failed Provisioning</h2>
                    <p class="text-2xl font-semibold text-gray-800">{{ $totalFailed }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-lg shadow">
        <livewire:saccos-management />
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-1">
                    <input wire:model.live="search" type="text" placeholder="Search by alias..."
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="w-full md:w-48">
                    <select wire:model.live="statusFilter" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SACCO</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Started</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($statuses as $status)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $status->alias }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span @class([
                                    'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                                    'bg-yellow-100 text-yellow-800' => $status->status === 'pending',
                                    'bg-blue-100 text-blue-800' => $status->status === 'in_progress',
                                    'bg-green-100 text-green-800' => $status->status === 'completed',
                                    'bg-red-100 text-red-800' => $status->status === 'failed',
                                ])>
                                    {{ ucfirst($status->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $status->progress }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 mt-2 block">{{ $status->progress }}%</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 space-y-2">
                                    @if($status->step)
                                        <div class="font-medium">Current Step: {{ $this->getStepDescription($status->step) }}</div>
                                        @if($status->status === 'in_progress')
                                            <div class="space-y-2">
                                                <div class="flex items-center text-xs text-gray-500">
                                                    <span>Progress: {{ $status->progress }}%</span>
                                                    @if($nextStep = $this->getNextStep($status->step))
                                                        <span class="mx-2">→</span>
                                                        <span>Next: {{ $this->getStepDescription($nextStep) }}</span>
                                                    @endif
                                                </div>
                                                @if($timeRemaining = $this->getEstimatedTimeRemaining($status))
                                                    <div class="text-xs text-gray-500">
                                                        Estimated time remaining: {{ $timeRemaining }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endif
                                    @if($status->message)
                                        <div class="text-gray-600">{{ $status->message }}</div>
                                    @endif
                                    <div class="flex space-x-4">
                                        @if($status->status === 'failed' && $status->data)
                                            <button wire:click="$set('showErrorDetails', {{ $status->id }})"
                                                    class="text-red-600 hover:text-red-900 text-sm">
                                                Show Error Details
                                            </button>
                                        @endif
                                        <button wire:click="getLogs({{ $status->id }})"
                                                class="text-blue-600 hover:text-blue-900 text-sm">
                                            View Logs
                                        </button>
                                    </div>
                                    @if($showErrorDetails === $status->id)
                                        <div class="mt-2 p-4 bg-red-50 rounded-lg">
                                            <pre class="text-xs text-red-800 overflow-x-auto">{{ json_encode($status->data, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    @endif
                                    @if($showLogs && $showLogs['provisioning'])
                                        <div class="mt-4">
                                            <div class="border rounded-lg overflow-hidden">
                                                <div class="bg-gray-50 px-4 py-3 border-b">
                                                    <h4 class="text-sm font-medium text-gray-700">Provisioning Logs</h4>
                                                </div>
                                                <div class="p-4 space-y-4">
                                                    @if($showLogs['provisioning'])
                                                        <div>
                                                            <h5 class="text-xs font-medium text-gray-500 mb-2">Main Log</h5>
                                                            <pre class="text-xs bg-gray-50 p-3 rounded overflow-x-auto max-h-40">{{ $showLogs['provisioning'] }}</pre>
                                                        </div>
                                                    @endif
                                                    @if($showLogs['error'])
                                                        <div>
                                                            <h5 class="text-xs font-medium text-gray-500 mb-2">Error Log</h5>
                                                            <pre class="text-xs bg-red-50 p-3 rounded overflow-x-auto max-h-40">{{ $showLogs['error'] }}</pre>
                                                        </div>
                                                    @endif
                                                    @if($showLogs['debug'])
                                                        <div>
                                                            <h5 class="text-xs font-medium text-gray-500 mb-2">Debug Log</h5>
                                                            <pre class="text-xs bg-blue-50 p-3 rounded overflow-x-auto max-h-40">{{ $showLogs['debug'] }}</pre>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $status->created_at->diffForHumans() }}
                                @if($status->status === 'in_progress')
                                    <div class="text-xs text-gray-400 mt-1">
                                        Running for {{ $status->started_at->diffForHumans() }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($status->status === 'failed')
                                    <button wire:click="retryProvisioning({{ $status->id }})"
                                            class="text-blue-600 hover:text-blue-900">Retry</button>
                                @endif
                                @if($status->status === 'completed')
                                    <a href="{{ route('sacco.dashboard', ['alias' => $status->alias]) }}"
                                       class="text-green-600 hover:text-green-900">View</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No provisioning records found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $statuses->links() }}
        </div>
    </div>
</div>
