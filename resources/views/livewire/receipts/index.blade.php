<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Receipts</h1>
        <a href="{{ route('receipts.create') }}" wire:navigate
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Receipt
        </a>
    </div>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 text-sm font-medium text-green-800 bg-green-50 border border-green-200 rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 space-y-3">
            <div class="flex items-center gap-3">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" placeholder="Search receipts..." wire:model.live.debounce.300ms="search"
                           class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <div class="flex gap-2 flex-wrap" x-data>
                @php
                    $filters = [
                        null => 'All',
                        'pending' => 'Pending',
                        'processed' => 'Processed',
                        'failed' => 'Failed',
                    ];
                @endphp
                @foreach ($filters as $value => $label)
                    @php
                        $active = $statusFilter === $value;
                    @endphp
                    <button wire:click="filterByStatus('{{ $value }}')"
                            class="px-3 py-1.5 text-xs font-medium rounded-full border transition-colors
                                   {{ $active ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse ($receipts as $receipt)
                <div class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('receipts.show', $receipt) }}" wire:navigate class="block">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ Str::limit($receipt->source_text, 80) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ $receipt->created_at->format('M d, Y H:i') }}
                                </p>
                            </a>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-xs text-gray-500">{{ $receipt->expenses_count }} items</span>
                            @php
                                $classes = [
                                    'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    'processed' => 'bg-green-50 text-green-700 border-green-200',
                                    'failed' => 'bg-red-50 text-red-700 border-red-200',
                                ];
                                $class = $classes[$receipt->status->value] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                            @endphp
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full border {{ $class }}">
                                {{ $receipt->status->label() }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="mx-auto w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <p class="mt-4 text-sm font-medium text-gray-900">No receipts yet</p>
                    <p class="mt-1 text-sm text-gray-500">Submit your first receipt to get started.</p>
                    <a href="{{ route('receipts.create') }}" wire:navigate
                       class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        Submit a receipt &rarr;
                    </a>
                </div>
            @endforelse
        </div>

        @if ($receipts->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $receipts->links() }}
            </div>
        @endif
    </div>
</div>
