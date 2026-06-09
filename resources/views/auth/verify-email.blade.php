<x-guest-layout>
    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 rounded-lg p-3">
            A new verification link has been sent to your email.
        </div>
    @endif

    <div class="text-sm text-gray-600 mb-6">
        Thanks for signing up! Before getting started, please verify your email by clicking the link we just sent you.
    </div>

    <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
        @csrf
        <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
            Resend Verification Email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="w-full text-center text-sm text-gray-500 hover:text-gray-700 font-medium">
            Log Out
        </button>
    </form>
</x-guest-layout>
