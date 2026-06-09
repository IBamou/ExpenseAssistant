<x-guest-layout>
    <div class="text-sm text-gray-600 mb-6">
        Forgot your password? No problem. Just enter your email and we'll send you a reset link.
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 rounded-lg p-3">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-2.5"
                       placeholder="you@example.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Send Reset Link
            </button>
        </div>

        <p class="mt-6 text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">Back to sign in</a>
        </p>
    </form>
</x-guest-layout>
