<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Submit a Receipt</h1>
        <p class="mt-1 text-sm text-gray-500">Paste the supplier receipt text below. Our AI will extract the expenses automatically.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 text-sm font-medium text-green-800 bg-green-50 border border-green-200 rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" x-data="{ charCount: {{ strlen($source_text) }} }">
        <form wire:submit="submit">
            <div class="space-y-5">
                <div>
                    <label for="source_text" class="block text-sm font-medium text-gray-700 mb-1.5">Receipt Text</label>
                    <textarea
                        id="source_text"
                        wire:model.live="source_text"
                        rows="12"
                        x-on:input="charCount = $event.target.value.length"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-2.5"
                        placeholder="Paste the supplier receipt text here...&#10;&#10;Example:&#10;3x Lait Danone 12.50&#10;2x Huile Oléor 45.00&#10;5x Pain 2.50"></textarea>
                    @error('source_text')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p x-text="charCount + ' / 10000 characters'" class="mt-1 text-xs text-gray-400"></p>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 disabled:opacity-50 transition-colors shadow-sm">
                        <svg wire:loading wire:target="submit" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        Submit for Processing
                    </button>
                    <a href="{{ route('receipts.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-700 font-medium">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
