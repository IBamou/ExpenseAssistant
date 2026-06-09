<section>
    <header>
        <h2 class="text-base font-semibold text-slate-900">Profile Information</h2>
        <p class="mt-1 text-sm text-slate-500">Update your account details and email address.</p>
    </header>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                   class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-sea-500 focus:ring-sea-500 text-sm px-4 py-2.5">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                   class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-sea-500 focus:ring-sea-500 text-sm px-4 py-2.5">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-slate-600">Your email is unverified.
                        <button form="send-verification" class="text-sea-600 hover:text-sea-500 font-medium">Click here to re-send verification.</button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 text-sm font-medium text-green-600">A new verification link has been sent.</p>
                    @endif
                </div>
            @endif
        </div>
        <div class="flex items-center gap-4">
            <button type="submit" class="px-4 py-2.5 bg-sea-600 text-white text-sm font-semibold rounded-lg hover:bg-sea-700 transition-colors shadow-sm shadow-sea-200/50">
                <i class="fas fa-save mr-1"></i>Save
            </button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium">
                    <i class="fas fa-check-circle mr-1"></i>Saved.
                </p>
            @endif
        </div>
    </form>
</section>
