<div>
    <div class="mb-6">
        <a href="{{ route('receipts.index') }}" wire:navigate class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 font-medium mb-4">
            <i class="fas fa-arrow-left text-xs"></i>
            Back to receipts
        </a>
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-bold text-slate-900">Receipt Details</h1>
            @php
                $classes = [
                    'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                    'processed' => 'bg-green-50 text-green-700 border-green-200',
                    'failed' => 'bg-red-50 text-red-700 border-red-200',
                ];
                $class = $classes[$receipt->status->value] ?? 'bg-slate-50 text-slate-700 border-slate-200';
            @endphp
            <span class="px-3 py-1 text-xs font-medium rounded-full border {{ $class }}">
                <i class="fas fa-circle text-[8px] mr-1.5 {{ $receipt->status->value === 'pending' ? 'text-yellow-500' : ($receipt->status->value === 'processed' ? 'text-green-500' : 'text-red-500') }}"></i>
                {{ $receipt->status->label() }}
            </span>
        </div>
        <p class="text-sm text-slate-500 mt-1"><i class="far fa-calendar-alt mr-1"></i>Submitted {{ $receipt->created_at->format('M d, Y H:i') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3"><i class="fas fa-align-left mr-1"></i>Source Text</h2>
                <pre class="text-sm text-slate-700 whitespace-pre-wrap bg-slate-50 rounded-lg p-4 border border-slate-100 leading-relaxed">{{ $receipt->source_text }}</pre>
            </div>

            @if ($receipt->status->value === 'processed' && $receipt->expenses->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100">
                        <h2 class="text-xs font-semibold text-slate-500 uppercase tracking-wider"><i class="fas fa-list mr-1"></i>Extracted Expenses</h2>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach ($receipt->expenses as $expense)
                            <div class="px-6 py-3 flex items-center justify-between text-sm">
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-slate-900">{{ $expense->label }}</p>
                                    <p class="text-xs text-slate-500">{{ $expense->category->label() }}</p>
                                </div>
                                <div class="flex items-center gap-6 shrink-0">
                                    <span class="text-slate-500">{{ $expense->quantity }}x</span>
                                    <span class="text-slate-500">{{ number_format($expense->unit_price, 2) }}</span>
                                    <span class="font-medium text-slate-900 w-16 text-right">{{ number_format($expense->quantity * $expense->unit_price, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @php
                        $total = $receipt->expenses->sum(fn($e) => $e->quantity * $e->unit_price);
                    @endphp
                    <div class="px-6 py-3 bg-sea-50 flex items-center justify-between text-sm font-semibold">
                        <span class="text-sea-700"><i class="fas fa-calculator mr-1"></i>Total</span>
                        <span class="text-sea-800">{{ number_format($total, 2) }} MAD</span>
                    </div>
                </div>
            @elseif ($receipt->status->value === 'processed' && $receipt->expenses->count() === 0)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 text-center">
                    <i class="fas fa-inbox text-2xl text-slate-300"></i>
                    <p class="mt-2 text-sm text-slate-500">No expenses were extracted from this receipt.</p>
                </div>
            @elseif ($receipt->status->value === 'pending')
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-clock text-yellow-600 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">Processing</p>
                            <p class="text-sm text-yellow-700 mt-1">Expenses will appear here once extraction is complete.</p>
                        </div>
                    </div>
                </div>
            @elseif ($receipt->status->value === 'failed')
                <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-medium text-red-800">Processing Failed</p>
                            <p class="text-sm text-red-700 mt-1">Something went wrong. Try submitting the receipt again.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4"><i class="fas fa-cog mr-1"></i>Actions</h2>
                <div class="space-y-3">
                    @unless ($confirmingDelete)
                        <button wire:click="confirmDelete"
                                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                            <i class="fas fa-trash-alt"></i>
                            Delete Receipt
                        </button>
                    @else
                        <div class="space-y-2">
                            <p class="text-sm text-slate-600">Are you sure you want to delete this receipt and all its expenses?</p>
                            <div class="flex gap-2">
                                <button wire:click="delete"
                                        class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                    <i class="fas fa-check mr-1"></i>Yes, Delete
                                </button>
                                <button wire:click="cancelDelete"
                                        class="flex-1 px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    @endunless
                </div>
            </div>

            @if ($receipt->expenses->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4"><i class="fas fa-chart-pie mr-1"></i>Summary</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Total Items</span>
                            <span class="font-medium text-slate-900">{{ $receipt->expenses->count() }}</span>
                        </div>
                        @php
                            $byCategory = $receipt->expenses->groupBy(fn($e) => $e->category->label());
                        @endphp
                        @foreach ($byCategory as $cat => $items)
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">{{ $cat }}</span>
                                <span class="font-medium text-slate-900">{{ $items->count() }}</span>
                            </div>
                        @endforeach
                        <div class="pt-3 border-t border-slate-100 flex justify-between text-sm font-semibold">
                            <span class="text-slate-900">Estimated Total</span>
                            <span class="text-sea-700">{{ number_format($total ?? 0, 2) }} MAD</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
