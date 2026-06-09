<x-app-layout>
    <div>
        <h1 class="text-xl font-bold text-slate-900 mb-6">Dashboard</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @php
                $total = \App\Models\Receipt::where('user_id', auth()->id())->count();
                $processed = \App\Models\Receipt::where('user_id', auth()->id())->where('status', 'processed')->count();
                $pending = \App\Models\Receipt::where('user_id', auth()->id())->where('status', 'pending')->count();
                $failed = \App\Models\Receipt::where('user_id', auth()->id())->where('status', 'failed')->count();
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-sea-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-invoice text-sea-600"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $total }}</p>
                        <p class="text-sm text-slate-500">Total Receipts</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $processed }}</p>
                        <p class="text-sm text-slate-500">Processed</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $pending }}</p>
                        <p class="text-sm text-slate-500">Pending</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-circle text-red-600"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $failed }}</p>
                        <p class="text-sm text-slate-500">Failed</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-base font-semibold text-slate-900 mb-4"><i class="fas fa-bolt text-sea-600 mr-2"></i>Quick Actions</h2>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('receipts.create') }}" wire:navigate
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-sea-600 text-white text-sm font-semibold rounded-lg hover:bg-sea-700 transition-colors shadow-sm shadow-sea-200/50">
                    <i class="fas fa-plus"></i>
                    Submit a Receipt
                </a>
                <a href="{{ route('receipts.index') }}" wire:navigate
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-slate-700 text-sm font-semibold rounded-lg border border-slate-300 hover:bg-slate-50 transition-colors shadow-sm">
                    <i class="fas fa-file-invoice"></i>
                    View All Receipts
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
