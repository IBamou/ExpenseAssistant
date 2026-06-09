<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-slate-900">Receipts</h1>
        <a href="{{ route('receipts.create') }}" wire:navigate
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-sea-600 text-white text-sm font-semibold rounded-lg hover:bg-sea-700 transition-colors shadow-sm shadow-sea-200/50">
            <i class="fas fa-plus text-xs"></i>
            New Receipt
        </a>
    </div>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 text-sm font-medium text-green-800 bg-green-50 border border-green-200 rounded-lg">
            <i class="fas fa-check-circle mr-1"></i>{{ session('status') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 border-b border-slate-200 space-y-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" placeholder="Search receipts..." wire:model.live.debounce.300ms="search"
                       class="w-full pl-9 pr-4 py-2 text-sm border border-slate-300 rounded-lg focus:border-sea-500 focus:ring-sea-500">
            </div>
            <div class="flex gap-2 flex-wrap">
                @php
                    $filters = [
                        null => 'All',
                        'pending' => 'Pending',
                        'processed' => 'Processed',
                        'failed' => 'Failed',
                    ];
                @endphp
                @foreach ($filters as $value => $label)
                    @php $active = $statusFilter === $value; @endphp
                    <button wire:click="filterByStatus('{{ $value }}')"
                            class="px-3 py-1.5 text-xs font-medium rounded-full border transition-colors
                                   {{ $active ? 'bg-sea-600 text-white border-sea-600' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse ($receipts as $receipt)
                <div class="p-4 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('receipts.show', $receipt) }}" wire:navigate class="block">
                                <p class="text-sm font-medium text-slate-900 truncate">
                                    {{ Str::limit($receipt->source_text, 80) }}
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    <i class="far fa-calendar-alt mr-1"></i>{{ $receipt->created_at->format('M d, Y H:i') }}
                                </p>
                            </a>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-xs text-slate-500"><i class="far fa-receipt mr-1"></i>{{ $receipt->expenses_count }} items</span>
                            @php
                                $classes = [
                                    'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    'processed' => 'bg-green-50 text-green-700 border-green-200',
                                    'failed' => 'bg-red-50 text-red-700 border-red-200',
                                ];
                                $class = $classes[$receipt->status->value] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                            @endphp
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full border {{ $class }}">
                                {{ $receipt->status->label() }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <i class="fas fa-file-invoice text-4xl text-slate-300"></i>
                    <p class="mt-4 text-sm font-medium text-slate-900">No receipts yet</p>
                    <p class="mt-1 text-sm text-slate-500">Submit your first receipt to get started.</p>
                    <a href="{{ route('receipts.create') }}" wire:navigate
                       class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-sea-600 hover:text-sea-500">
                        Submit a receipt <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            @endforelse
        </div>

        @if ($receipts->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $receipts->links() }}
            </div>
        @endif
    </div>
</div>
