<section>
    <header>
        <h2 class="text-base font-semibold text-slate-900">Update Password</h2>
        <p class="mt-1 text-sm text-slate-500">Ensure your account uses a strong password.</p>
    </header>
    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')
        <div>
            <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1">Current Password</label>
            <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                   class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-sea-500 focus:ring-sea-500 text-sm px-4 py-2.5">
            @error('current_password', 'updatePassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">New Password</label>
            <input id="password" name="password" type="password" autocomplete="new-password"
                   class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-sea-500 focus:ring-sea-500 text-sm px-4 py-2.5">
            @error('password', 'updatePassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                   class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-sea-500 focus:ring-sea-500 text-sm px-4 py-2.5">
            @error('password_confirmation', 'updatePassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex items-center gap-4">
            <button type="submit" class="px-4 py-2.5 bg-sea-600 text-white text-sm font-semibold rounded-lg hover:bg-sea-700 transition-colors shadow-sm shadow-sea-200/50">
                <i class="fas fa-save mr-1"></i>Save
            </button>
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium">
                    <i class="fas fa-check-circle mr-1"></i>Saved.
                </p>
            @endif
        </div>
    </form>
</section>
