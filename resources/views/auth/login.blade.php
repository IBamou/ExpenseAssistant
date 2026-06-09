<x-guest-layout>
    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 rounded-lg p-3">
            <i class="fas fa-check-circle mr-1"></i>{{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-sea-500 focus:ring-sea-500 text-sm px-4 py-2.5"
                       placeholder="you@example.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-sea-500 focus:ring-sea-500 text-sm px-4 py-2.5"
                       placeholder="Enter your password">
                @error('password')
                    <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-sea-600 focus:ring-sea-500">
                    <span class="text-sm text-slate-600">Remember me</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-sea-600 hover:text-sea-500 font-medium">Forgot password?</a>
                @endif
            </div>
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-sea-600 hover:bg-sea-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sea-500 transition-colors">
                <i class="fas fa-sign-in-alt mr-2"></i>Sign in
            </button>
        </div>
        <p class="mt-6 text-center text-sm text-slate-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-sea-600 hover:text-sea-500 font-medium">Sign up</a>
        </p>
    </form>
</x-guest-layout>
