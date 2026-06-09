<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                       class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-sea-500 focus:ring-sea-500 text-sm px-4 py-2.5">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-sea-500 focus:ring-sea-500 text-sm px-4 py-2.5"
                       placeholder="Min 8 characters">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-sea-500 focus:ring-sea-500 text-sm px-4 py-2.5"
                       placeholder="Repeat your password">
                @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-sea-600 hover:bg-sea-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sea-500 transition-colors">
                <i class="fas fa-key mr-2"></i>Reset Password
            </button>
        </div>
    </form>
</x-guest-layout>
