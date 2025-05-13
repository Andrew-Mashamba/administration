<x-app-layout>

    <div>

        <div x-data="{ open: false }" class="flex h-screen bg-gray-100">
            @livewire('side-bar')
            <div class="flex-1 flex flex-col overflow-hidden">

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                    <div class="container mx-auto px-6 py-8">
                        <h3 class="text-gray-700 text-3xl font-medium">Welcome to your dashboard!</h3>
                        <div class="mt-4">
                            <div class="flex flex-col -mx-6">
                                <div class="px-6 py-4">
                                    <p class="text-gray-600">This is a sample dashboard. You can replace this with your actual content.</p>

                                    @livewire('saccos-management')
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</x-app-layout>
