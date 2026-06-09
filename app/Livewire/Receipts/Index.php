<?php

namespace App\Livewire\Receipts;

use App\Models\Receipt;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('My Receipts')]
#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public ?string $statusFilter = null;

    public string $search = '';

    public function filterByStatus(?string $status): void
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Receipt::where('user_id', auth()->id())
            ->withCount('expenses')
            ->latest();

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->search) {
            $query->where('source_text', 'like', '%'.$this->search.'%');
        }

        $receipts = $query->paginate(10);

        return view('livewire.receipts.index', [
            'receipts' => $receipts,
        ]);
    }
}
