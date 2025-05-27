<div>
        

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Institutions</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $totalInstitutions }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Institutions</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $activeInstitutions }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Institutions</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $pendingInstitutions }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Inactive Institutions</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $inactiveInstitutions }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-2">
            <div class="p-2">
                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">
                            <strong class="font-bold">Error: </strong>
                            {{ session('error') }}
                        </span>
                        <button wire:click="$set('error', null)" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Close</title>
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                            </svg>
                        </button>
                    </div>
                @endif

                @if(!$isEditing && !$isViewing && !$isCreating)
                <div class="mb-4">
                    <button wire:click="createSaccos" class="bg-blue-800 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="createSaccos">Create Institution</span>
                        <span wire:loading wire:target="createSaccos">Creating...</span>
                    </button>
                </div>
                @endif

                @if($isCreating)
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold mb-2">Create Institution</h3>
                        <form wire:submit.prevent="saveSaccos" class="space-y-6">
                            <!-- Basic Information -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-md font-semibold mb-4">Basic Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                                        <input type="text" wire:model="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="institution_type" class="block text-gray-700 text-sm font-bold mb-2">Institution Type</label>
                                        <select wire:model="institution_type" id="institution_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            <option value="saccos">Saccos</option>
                                            <option value="microfinance">Microfinance</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Location</label>
                                        <input type="text" wire:model="location" id="location" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @error('location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="contact_person" class="block text-gray-700 text-sm font-bold mb-2">Contact Person</label>
                                        <input type="text" wire:model="contact_person" id="contact_person" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @error('contact_person') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
                                        <input type="text" wire:model="phone" id="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                                        <input type="email" wire:model="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Database Information -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-md font-semibold mb-4">Database Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="alias" class="block text-gray-700 text-sm font-bold mb-2">Alias</label>
                                        <input type="text" wire:model="alias" id="alias" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @error('alias') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="db_name" class="block text-gray-700 text-sm font-bold mb-2">Database Name</label>
                                        <input type="text" wire:model="db_name" id="db_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @error('db_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="db_user" class="block text-gray-700 text-sm font-bold mb-2">Database User</label>
                                        <input type="text" wire:model="db_user" id="db_user" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @error('db_user') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="db_password" class="block text-gray-700 text-sm font-bold mb-2">Database Password</label>
                                        <input type="password" wire:model="db_password" id="db_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @error('db_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="institution_id" class="block text-gray-700 text-sm font-bold mb-2">Institution ID</label>
                                        <input type="text" wire:model="institution_id" id="institution_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @error('institution_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Manager Information -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-md font-semibold mb-4">Manager Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="manager_email" class="block text-gray-700 text-sm font-bold mb-2">Manager Email</label>
                                        <input type="email" wire:model="manager_email" id="manager_email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @error('manager_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="manager_phone_number" class="block text-gray-700 text-sm font-bold mb-2">Manager Phone</label>
                                        <input type="text" wire:model="manager_phone_number" id="manager_phone_number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @error('manager_phone_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- IT Information -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-md font-semibold mb-4">IT Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="it_email" class="block text-gray-700 text-sm font-bold mb-2">IT Email</label>
                                        <input type="email" wire:model="it_email" id="it_email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @error('it_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="it_phone_number" class="block text-gray-700 text-sm font-bold mb-2">IT Phone</label>
                                        <input type="text" wire:model="it_phone_number" id="it_phone_number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        @error('it_phone_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-4">
                                <button type="button" wire:click="cancelCreate" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" wire:loading.attr="disabled">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-gray-700 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="saveSaccos">Save Institution</span>
                                    <span wire:loading wire:target="saveSaccos">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Saving...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                @if($isViewing)
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold mb-2">View Institution</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-md font-semibold mb-2">Basic Information</h4>
                                <p><strong>Name:</strong> {{ $saccos->name }}</p>
                                <p><strong>Institution Type:</strong> {{ $saccos->institution_type }}</p>
                                <p><strong>Status:</strong> {{ ucfirst($saccos->status) }}</p>
                                <p><strong>Location:</strong> {{ $saccos->location }}</p>
                                <p><strong>Contact Person:</strong> {{ $saccos->contact_person }}</p>
                                <p><strong>Phone:</strong> {{ $saccos->phone }}</p>
                                <p><strong>Email:</strong> {{ $saccos->email }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-md font-semibold mb-2">Database Information</h4>
                                <p><strong>Alias:</strong> {{ $saccos->alias }}</p>
                                <p><strong>Database Name:</strong> {{ $saccos->db_name }}</p>
                                <p><strong>Database User:</strong> {{ $saccos->db_user }}</p>
                                <p><strong>Institution ID:</strong> {{ $saccos->institution_id }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-md font-semibold mb-2">Manager Information</h4>
                                <p><strong>Manager Email:</strong> {{ $saccos->manager_email }}</p>
                                <p><strong>Manager Phone:</strong> {{ $saccos->manager_phone_number }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-md font-semibold mb-2">IT Information</h4>
                                <p><strong>IT Email:</strong> {{ $saccos->it_email }}</p>
                                <p><strong>IT Phone:</strong> {{ $saccos->it_phone_number }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button wire:click="softDeleteSaccos({{ $saccos->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Soft Delete
                            </button>
                            <!-- back to list button -->
                            <button wire:click="cancelView" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to List
                            </button>
                        </div>
                    </div>
                @endif

                @if($isEditing)
                                    <div class="mb-4">
                                        <h3 class="text-lg font-semibold mb-2">Edit Institution</h3>
                                        <form wire:submit.prevent="updateSaccos" class="space-y-6">                                         
                                                
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <h4 class="text-md font-semibold mb-4">Basic Information</h4>                                               
                                               
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                                                        <input type="text" wire:model="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div>
                                                        <label for="institution_type" class="block text-gray-700 text-sm font-bold mb-2">Institution Type</label>
                                                        <select wire:model="institution_type" id="institution_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                            <option value="">Select Institution Type</option>
                                                            <option value="saccos">Saccos</option>
                                                            <option value="microfinance">Microfinance</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Location</label>
                                                        <input type="text" wire:model="location" id="location" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        @error('location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div>
                                                        <label for="contact_person" class="block text-gray-700 text-sm font-bold mb-2">Contact Person</label>
                                                        <input type="text" wire:model="contact_person" id="contact_person" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        @error('contact_person') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div>
                                                        <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
                                                        <input type="text" wire:model="phone" id="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div>
                                                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                                                        <input type="email" wire:model="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Database Information -->
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <h4 class="text-md font-semibold mb-4">Database Information</h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label for="alias" class="block text-gray-700 text-sm font-bold mb-2">Alias</label>
                                                        <input type="text" wire:model="alias" id="alias" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        @error('alias') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div>
                                                        <label for="db_name" class="block text-gray-700 text-sm font-bold mb-2">Database Name</label>
                                                        <input type="text" wire:model="db_name" id="db_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        @error('db_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div>
                                                        <label for="db_user" class="block text-gray-700 text-sm font-bold mb-2">Database User</label>
                                                        <input type="text" wire:model="db_user" id="db_user" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        @error('db_user') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div>
                                                        <label for="db_password" class="block text-gray-700 text-sm font-bold mb-2">Database Password</label>
                                                        <input type="password" wire:model="db_password" id="db_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        @error('db_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div>
                                                        <label for="institution_id" class="block text-gray-700 text-sm font-bold mb-2">Institution ID</label>
                                                        <input type="text" wire:model="institution_id" id="institution_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        @error('institution_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Manager Information -->
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <h4 class="text-md font-semibold mb-4">Manager Information</h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label for="manager_email" class="block text-gray-700 text-sm font-bold mb-2">Manager Email</label>
                                                        <input type="email" wire:model="manager_email" id="manager_email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        @error('manager_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div>
                                                        <label for="manager_phone_number" class="block text-gray-700 text-sm font-bold mb-2">Manager Phone</label>
                                                        <input type="text" wire:model="manager_phone_number" id="manager_phone_number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        @error('manager_phone_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- IT Information -->
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <h4 class="text-md font-semibold mb-4">IT Information</h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label for="it_email" class="block text-gray-700 text-sm font-bold mb-2">IT Email</label>
                                                        <input type="email" wire:model="it_email" id="it_email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        @error('it_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div>
                                                        <label for="it_phone_number" class="block text-gray-700 text-sm font-bold mb-2">IT Phone</label>
                                                        <input type="text" wire:model="it_phone_number" id="it_phone_number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        @error('it_phone_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex justify-end space-x-4">
                                                <button type="button" wire:click="cancelEdit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" wire:loading.attr="disabled">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="bg-gray-700 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" wire:loading.attr="disabled">
                                                    <span wire:loading.remove wire:target="updateSaccos">Update Institution</span>
                                                    <span wire:loading wire:target="updateSaccos">
                                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        Updating...
                                                    </span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                @endif

                @if(!$isEditing && !$isViewing && !$isCreating)
                <div>
                    <h3 class="text-lg font-semibold mb-2">Institutions List</h3>
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" wire:model.live="search" placeholder="Search institutions by name..." 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">Name</th>
                                    <th class="py-2 px-4 border-b">Institution Type</th>                                    
                                    <th class="py-2 px-4 border-b">Location</th>  
                                    <th class="py-2 px-4 border-b">Status</th>                                  
                                    <th class="py-2 px-4 border-b">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($saccosList as $saccos)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $saccos->name }}</td>
                                        <td class="py-2 px-4 border-b">{{ $saccos->institution_type }}</td>
                                        <td class="py-2 px-4 border-b">{{ $saccos->location }}</td>
                                        <td class="py-2 px-4 border-b">
                                            <button wire:click="toggleStatus({{ $saccos->id }})" 
                                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium transition-colors duration-200
                                                @if($saccos->status == 'active') 
                                                    bg-green-100 text-green-800 hover:bg-green-200 
                                                @else 
                                                    bg-red-100 text-red-800 hover:bg-red-200 
                                                @endif" 
                                                wire:loading.attr="disabled" 
                                                title="Change Status">
                                                <span wire:loading.remove wire:target="toggleStatus({{ $saccos->id }})" class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        @if($saccos->status == 'active')
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        @else
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        @endif
                                                    </svg>
                                                    {{ ucfirst($saccos->status) }}
                                                </span>
                                                <span wire:loading wire:target="toggleStatus({{ $saccos->id }})" class="flex items-center">
                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Loading...
                                                </span>
                                            </button>
                                        </td>                                        
                                        <td class="py-2 px-4 border-b">
                                            <div class="flex items-center space-x-3">
                                                <button wire:click="viewSaccos({{ $saccos->id }})" 
                                                    class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors duration-200" 
                                                    wire:loading.attr="disabled">
                                                    <span wire:loading.remove wire:target="viewSaccos({{ $saccos->id }})" class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        View
                                                    </span>
                                                    <span wire:loading wire:target="viewSaccos({{ $saccos->id }})" class="flex items-center">
                                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        Loading...
                                                    </span>
                                                </button>
                                                <button wire:click="editSaccos({{ $saccos->id }})" 
                                                    class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-yellow-700 bg-yellow-100 hover:bg-yellow-200 transition-colors duration-200" 
                                                    wire:loading.attr="disabled">
                                                    <span wire:loading.remove wire:target="editSaccos({{ $saccos->id }})" class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Edit
                                                    </span>
                                                    <span wire:loading wire:target="editSaccos({{ $saccos->id }})" class="flex items-center">
                                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        Loading...
                                                    </span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $saccosList->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    
</div>
