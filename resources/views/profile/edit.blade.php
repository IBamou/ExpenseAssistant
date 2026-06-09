<x-app-layout>
    <div class="max-w-3xl">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Profile</h1>

        <div class="space-y-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
