<?php

namespace App\Http\Controllers;

use App\Enums\ReceiptStatus;
use App\Http\Requests\StoreReceiptRequest;
use App\Jobs\ExtractExpenses;
use App\Models\Receipt;

class ReceiptController extends Controller
{
    public function index()
    {
        $receipts = Receipt::where('user_id', auth()->id())
            ->withCount('expenses')
            ->latest()
            ->paginate(15);

        return view('receipts.index', compact('receipts'));
    }

    public function create()
    {
        return view('receipts.create');
    }

    public function store(StoreReceiptRequest $request)
    {
        $receipt = auth()->user()->receipts()->create([
            ...$request->validated(),
            'status' => ReceiptStatus::Pending,
            'expenses_count' => 0,
        ]);

        ExtractExpenses::dispatch($receipt);

        return redirect()->route('receipts.index')
            ->with('status', 'Receipt submitted for processing.');
    }

    public function show(Receipt $receipt)
    {
        $this->authorize('view', $receipt);

        $receipt->load('expenses');

        return view('receipts.show', compact('receipt'));
    }

    public function destroy(Receipt $receipt)
    {
        $this->authorize('delete', $receipt);

        $receipt->delete();

        return redirect()->route('receipts.index')
            ->with('status', 'Receipt deleted successfully.');
    }
}
