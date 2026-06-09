<section class="space-y-6">
    <header>
        <h2 class="text-base font-semibold text-slate-900">Delete Account</h2>
        <p class="mt-1 text-sm text-slate-500">Once deleted, all your data will be permanently removed.</p>
    </header>
    <div x-data="{ open: false }">
        <button type="button" @click="open = true"
                class="px-4 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-sm">
            <i class="fas fa-trash-alt mr-1"></i>Delete Account
        </button>
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50" style="display: none;">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6" @click.away="open = false">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    <h3 class="text-lg font-semibold text-slate-900">Are you sure?</h3>
                    <p class="mt-1 text-sm text-slate-500">Enter your password to confirm deletion.</p>
                    <div class="mt-4">
                        <input id="password" name="password" type="password" placeholder="Password"
                               class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm px-4 py-2.5">
                        @error('password', 'userDeletion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="open = false"
                                class="px-4 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                            <i class="fas fa-trash-alt mr-1"></i>Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
