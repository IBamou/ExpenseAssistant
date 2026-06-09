<?php

namespace App\Livewire\Receipts;

use App\Enums\ReceiptStatus;
use App\Jobs\ExtractExpenses;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Submit a Receipt')]
#[Layout('layouts.app')]
class Create extends Component
{
    #[Rule(['required', 'string', 'min:10', 'max:10000'])]
    public string $source_text = '';

    public function submit(): void
    {
        $this->validate();

        $receipt = auth()->user()->receipts()->create([
            'source_text' => $this->source_text,
            'status' => ReceiptStatus::Pending,
            'expenses_count' => 0,
        ]);

        ExtractExpenses::dispatch($receipt);

        session()->flash('status', 'Receipt submitted for processing.');

        $this->redirect(route('receipts.show', $receipt));
    }

    public function render()
    {
        return view('livewire.receipts.create');
    }
}
