<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Institution Manager</h2>

                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                <div class="mb-4">
                    <button wire:click="createSaccos" class="bg-gray-700 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Create Institution
                    </button>
                </div>

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

                            <div class="flex justify-end">
                                <button type="submit" class="bg-gray-700 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Save Institution
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
                        </div>
                    </div>
                @endif

                <div>
                    <h3 class="text-lg font-semibold mb-2">Institutions List</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">Name</th>
                                    <th class="py-2 px-4 border-b">Location</th>
                                    <th class="py-2 px-4 border-b">Contact Person</th>
                                    <th class="py-2 px-4 border-b">Phone</th>
                                    <th class="py-2 px-4 border-b">Email</th>
                                    <th class="py-2 px-4 border-b">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($saccosList as $saccos)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $saccos->name }}</td>
                                        <td class="py-2 px-4 border-b">{{ $saccos->location }}</td>
                                        <td class="py-2 px-4 border-b">{{ $saccos->contact_person }}</td>
                                        <td class="py-2 px-4 border-b">{{ $saccos->phone }}</td>
                                        <td class="py-2 px-4 border-b">{{ $saccos->email }}</td>
                                        <td class="py-2 px-4 border-b">
                                            <button wire:click="viewSaccos({{ $saccos->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">
                                                View
                                            </button>
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
            </div>
        </div>
    </div>
</div>
