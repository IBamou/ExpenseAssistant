<x-guest-layout>
    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 rounded-lg p-3">
            <i class="fas fa-check-circle mr-1"></i>A new verification link has been sent to your email.
        </div>
    @endif
    <div class="text-sm text-slate-600 mb-6">
        Thanks for signing up! Before getting started, please verify your email by clicking the link we just sent you.
    </div>
    <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
        @csrf
        <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-sea-600 hover:bg-sea-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sea-500 transition-colors">
            <i class="fas fa-envelope mr-2"></i>Resend Verification Email
        </button>
    </form>
    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="w-full text-center text-sm text-slate-500 hover:text-slate-700 font-medium">
            Log Out
        </button>
    </form>
</x-guest-layout>
