<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReceiptRequest;
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
        $receipt = auth()->user()->receipts()->create(
            $request->validated()
        );

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
