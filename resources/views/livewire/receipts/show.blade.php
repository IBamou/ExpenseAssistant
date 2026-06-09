<div>
    <div class="mb-6">
        <a href="{{ route('receipts.index') }}" wire:navigate class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 font-medium mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to receipts
        </a>
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Receipt Details</h1>
            @php
                $classes = [
                    'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                    'processed' => 'bg-green-50 text-green-700 border-green-200',
                    'failed' => 'bg-red-50 text-red-700 border-red-200',
                ];
                $class = $classes[$receipt->status->value] ?? 'bg-gray-50 text-gray-700 border-gray-200';
            @endphp
            <span class="px-3 py-1 text-xs font-medium rounded-full border {{ $class }}">
                {{ $receipt->status->label() }}
            </span>
        </div>
        <p class="text-sm text-gray-500 mt-1">Submitted {{ $receipt->created_at->format('M d, Y H:i') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3">Source Text</h2>
                <pre class="text-sm text-gray-700 whitespace-pre-wrap bg-gray-50 rounded-lg p-4 border border-gray-100 leading-relaxed">{{ $receipt->source_text }}</pre>
            </div>

            @if ($receipt->status->value === 'processed' && $receipt->expenses->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Extracted Expenses</h2>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach ($receipt->expenses as $expense)
                            <div class="px-6 py-3 flex items-center justify-between text-sm">
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900">{{ $expense->label }}</p>
                                    <p class="text-xs text-gray-500">{{ $expense->category->label() }}</p>
                                </div>
                                <div class="flex items-center gap-6 shrink-0">
                                    <span class="text-gray-500">{{ $expense->quantity }}x</span>
                                    <span class="text-gray-500">{{ number_format($expense->unit_price, 2) }}</span>
                                    <span class="font-medium text-gray-900 w-16 text-right">{{ number_format($expense->quantity * $expense->unit_price, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @php
                        $total = $receipt->expenses->sum(fn($e) => $e->quantity * $e->unit_price);
                    @endphp
                    <div class="px-6 py-3 bg-gray-50 flex items-center justify-between text-sm font-semibold">
                        <span class="text-gray-700">Total</span>
                        <span class="text-gray-900">{{ number_format($total, 2) }} MAD</span>
                    </div>
                </div>
            @elseif ($receipt->status->value === 'processed' && $receipt->expenses->count() === 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                    <p class="text-sm text-gray-500">No expenses were extracted from this receipt.</p>
                </div>
            @elseif ($receipt->status->value === 'pending')
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">Processing</p>
                            <p class="text-sm text-yellow-700 mt-1">Expenses will appear here once extraction is complete.</p>
                        </div>
                    </div>
                </div>
            @elseif ($receipt->status->value === 'failed')
                <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-red-800">Processing Failed</p>
                            <p class="text-sm text-red-700 mt-1">Something went wrong. Try submitting the receipt again.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Actions</h2>
                <div class="space-y-3">
                    @unless ($confirmingDelete)
                        <button wire:click="confirmDelete"
                                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Receipt
                        </button>
                    @else
                        <div class="space-y-2">
                            <p class="text-sm text-gray-600">Are you sure you want to delete this receipt and all its expenses?</p>
                            <div class="flex gap-2">
                                <button wire:click="delete"
                                        class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                    Yes, Delete
                                </button>
                                <button wire:click="cancelDelete"
                                        class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    @endunless
                </div>
            </div>

            @if ($receipt->expenses->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Summary</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total Items</span>
                            <span class="font-medium text-gray-900">{{ $receipt->expenses->count() }}</span>
                        </div>
                        @php
                            $byCategory = $receipt->expenses->groupBy(fn($e) => $e->category->label());
                        @endphp
                        @foreach ($byCategory as $cat => $items)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">{{ $cat }}</span>
                                <span class="font-medium text-gray-900">{{ $items->count() }}</span>
                            </div>
                        @endforeach
                        <div class="pt-3 border-t border-gray-100 flex justify-between text-sm font-semibold">
                            <span class="text-gray-900">Estimated Total</span>
                            <span class="text-gray-900">{{ number_format($total ?? 0, 2) }} MAD</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
