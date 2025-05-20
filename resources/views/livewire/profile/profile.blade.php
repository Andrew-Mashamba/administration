<div class="p-6">
    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <div class="max-w-7xl mx-auto">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                <div class="mt-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Profile Information</h2>
                    <p class="text-sm text-gray-600 mb-4">
                        Update your account's profile information and email address.
                    </p>

                    <form wire:submit.prevent="updateProfile">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" wire:model="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-nbc-primary focus:ring focus:ring-nbc-primary focus:ring-opacity-50">
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" wire:model="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-nbc-primary focus:ring focus:ring-nbc-primary focus:ring-opacity-50">
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-nbc-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-nbc-secondary focus:bg-nbc-secondary active:bg-nbc-secondary focus:outline-none focus:ring-2 focus:ring-nbc-primary focus:ring-offset-2 transition ease-in-out duration-150">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="mt-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Update Password</h2>
                    <p class="text-sm text-gray-600 mb-4">
                        Ensure your account is using a long, random password to stay secure.
                    </p>

                    <form wire:submit.prevent="updatePassword">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                                <input type="password" wire:model="current_password" id="current_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-nbc-primary focus:ring focus:ring-nbc-primary focus:ring-opacity-50">
                                @error('current_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                                <input type="password" wire:model="new_password" id="new_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-nbc-primary focus:ring focus:ring-nbc-primary focus:ring-opacity-50">
                                @error('new_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                <input type="password" wire:model="new_password_confirmation" id="new_password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-nbc-primary focus:ring focus:ring-nbc-primary focus:ring-opacity-50">
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-nbc-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-nbc-secondary focus:bg-nbc-secondary active:bg-nbc-secondary focus:outline-none focus:ring-2 focus:ring-nbc-primary focus:ring-offset-2 transition ease-in-out duration-150">
                                    Update Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 