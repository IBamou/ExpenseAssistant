<?php

namespace App\Livewire\Receipts;

use App\Models\Receipt;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Receipt Details')]
#[Layout('layouts.app')]
class Show extends Component
{
    public Receipt $receipt;

    public bool $confirmingDelete = false;

    public function mount(Receipt $receipt): void
    {
        $this->authorize('view', $receipt);

        $this->receipt = $receipt->load('expenses');
    }

    public function confirmDelete(): void
    {
        $this->confirmingDelete = true;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDelete = false;
    }

    public function delete(): void
    {
        $this->authorize('delete', $this->receipt);

        $this->receipt->delete();

        session()->flash('status', 'Receipt deleted successfully.');

        $this->redirectRoute('receipts.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.receipts.show');
    }
}
