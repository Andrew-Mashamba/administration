<div>
    <div wire:loading.remove>
        @switch($menu_id)
            @case(1)
                <livewire:dashboard />
                @break
            @case(2)
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Institutions</h3>
                        <p class="mt-1 text-sm text-gray-600">Manage SACCOs and view provisioning status.</p>
                    </div>
                    <livewire:provisioning-status-list />
                </div>
                @break
            @case(3)
                <livewire:users />
                @break
            @case(4)
                <livewire:settings />
                @break
            @case(9)
                <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center">
                    <div class="text-center">
                        <h2 class="text-2xl font-semibold text-red-600 mb-4">Account Status Issue</h2>
                        <p class="text-gray-600">Your account is not active or has been suspended. Please contact support for assistance.</p>
                    </div>
                </div>
                @break
            @default
                <livewire:dashboard />
        @endswitch
    </div>

    <div wire:loading class="min-h-[calc(100vh-4rem)] flex items-center justify-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
    </div>
</div>