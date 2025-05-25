<div>
    <div class="min-h-screen flex bg-gray-50">
        <div class="w-full mx-auto">
            <div class="bg-white shadow-xl sm:rounded-2xl overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Profile</h2>
                        <p class="mt-1 text-sm text-gray-500">Update your profile information</p>
                    </div>                   
                </div>

                <div class="p-6 space-y-8">
                    @livewire('profile.update-profile-information-form')
                </div>

                <div class="p-6 space-y-8">
                    @livewire('profile.update-password-form')
                </div>
            </div>
        </div>
    </div>
</div>

