<?php

namespace App\Livewire\Receipts;

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

    public bool $submitted = false;

    public function submit(): void
    {
        $this->validate();

        auth()->user()->receipts()->create([
            'source_text' => $this->source_text,
        ]);

        $this->submitted = true;
        $this->source_text = '';

        session()->flash('status', 'Receipt submitted for processing.');
    }

    public function render()
    {
        return view('livewire.receipts.create');
    }
}
