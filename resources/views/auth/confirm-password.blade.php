<x-guest-layout>
    <div class="text-sm text-slate-600 mb-6">
        This is a secure area. Please confirm your password before continuing.
    </div>
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="space-y-5">
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-sea-500 focus:ring-sea-500 text-sm px-4 py-2.5"
                       placeholder="Enter your password">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-sea-600 hover:bg-sea-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sea-500 transition-colors">
                <i class="fas fa-lock mr-2"></i>Confirm
            </button>
        </div>
    </form>
</x-guest-layout>
