<div class="flex justify-start mb-4">
    <button
        wire:click="$dispatch('open-modal')"
        wire:loading.attr="disabled"
        wire:target="$dispatch"
        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>

        <span wire:loading.remove wire:target="$dispatch">New User</span>
        <span wire:loading wire:target="$dispatch">Loading...</span>
    </button>
</div>
