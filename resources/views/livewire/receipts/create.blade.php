<div>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900">Submit a Receipt</h1>
        <p class="mt-1 text-sm text-slate-500">Paste the supplier receipt text below. The AI will extract the expenses automatically.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 text-sm font-medium text-green-800 bg-green-50 border border-green-200 rounded-lg">
            <i class="fas fa-check-circle mr-1"></i>{{ session('status') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6" x-data="{ charCount: {{ strlen($source_text) }} }">
        <form wire:submit="submit">
            <div class="space-y-5">
                <div>
                    <label for="source_text" class="block text-sm font-medium text-slate-700 mb-1.5">Receipt Text</label>
                    <textarea
                        id="source_text"
                        wire:model.live="source_text"
                        rows="12"
                        x-on:input="charCount = $event.target.value.length"
                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-sea-500 focus:ring-sea-500 text-sm px-4 py-2.5"
                        placeholder="Paste the supplier receipt text here...&#10;&#10;Example:&#10;3x Lait Danone 12.50&#10;2x Huile Oléor 45.00&#10;5x Pain 2.50"></textarea>
                    @error('source_text')
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                    <p x-text="charCount + ' / 10000 characters'" class="mt-1 text-xs text-slate-400"></p>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-sea-600 text-white text-sm font-semibold rounded-lg hover:bg-sea-700 disabled:opacity-50 transition-colors shadow-sm shadow-sea-200/50">
                        <i wire:loading wire:target="submit" class="fas fa-spinner fa-spin"></i>
                        <i wire:loading.remove wire:target="submit" class="fas fa-paper-plane"></i>
                        Submit for Processing
                    </button>
                    <a href="{{ route('receipts.index') }}" wire:navigate class="text-sm text-slate-500 hover:text-slate-700 font-medium">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
