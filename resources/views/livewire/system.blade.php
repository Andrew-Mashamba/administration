<div>
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
        <div wire:loading.remove class="min-h-[calc(100vh-4rem)]">
            @switch($menu_id)
                @case(1)
                    <livewire:dashboard.dashboard/>
                    @break
                @case(2)
                    <livewire:users.users />
                    @break
                @case(3)
                    <livewire:institutions.institution />
                    @break
                @case(4)
                    <livewire:reports.dashboard />
                    @break
                @case(5)
                    <livewire:profile.profile />
                    @break
                @case(9) {{-- special message for blocked, pending, deleted accounts--}}
                    <div class="p-4">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Account Status Issue!</strong>
                            <span class="block sm:inline"> Your account is either pending, blocked, or deleted. Please contact support for assistance.</span>
                        </div>
                    </div>
                    @break               
                @default
                    <livewire:dashboard.dashboard/>
            @endswitch
        </div>
    
</div>