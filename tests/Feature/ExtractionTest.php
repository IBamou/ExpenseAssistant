<?php

use App\Enums\ExpenseCategory;
use App\Enums\ReceiptStatus;
use App\Jobs\ExtractExpenses;
use App\Livewire\Receipts\Create;
use App\Models\Receipt;
use App\Models\User;
use Laravel\Ai\AnonymousAgent;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->receipt = Receipt::factory()->create([
        'user_id' => $this->user->id,
        'source_text' => "3x Lait Danone 12.50\n2x Huile Oleor 45.00\n5x Pain 2.50",
        'status' => ReceiptStatus::Pending,
    ]);
});

it('extracts expenses from valid ai response', function () {
    AnonymousAgent::fake(fn () => json_encode([
        'articles' => [
            [
                'label' => 'Lait Danone',
                'quantity' => 3,
                'unit_price' => 12.50,
                'category' => 'food',
            ],
            [
                'label' => 'Huile Oleor',
                'quantity' => 2,
                'unit_price' => 45.00,
                'category' => 'food',
            ],
        ],
        'total_estimated' => 127.50,
        'currency' => 'MAD',
    ]));

    (new ExtractExpenses($this->receipt))->handle();

    $this->receipt->refresh();

    expect($this->receipt->status)->toBe(ReceiptStatus::Processed);
    expect($this->receipt->expenses_count)->toBe(2);
    expect($this->receipt->raw_ai_payload)->toBeArray();

    $expenses = $this->receipt->expenses;
    expect($expenses)->toHaveCount(2);
    expect($expenses[0]->label)->toBe('Lait Danone');
    expect($expenses[0]->quantity)->toBe(3);
    expect((float) $expenses[0]->unit_price)->toBe(12.50);
    expect($expenses[0]->category)->toBe(ExpenseCategory::Food);
});

it('handles malformed ai response', function () {
    AnonymousAgent::fake(fn () => 'not json at all');

    (new ExtractExpenses($this->receipt))->handle();

    $this->receipt->refresh();

    expect($this->receipt->status)->toBe(ReceiptStatus::Failed);
    expect($this->receipt->expenses)->toHaveCount(0);
});

it('handles empty articles array', function () {
    AnonymousAgent::fake(fn () => json_encode([
        'articles' => [],
        'total_estimated' => 0,
        'currency' => 'MAD',
    ]));

    (new ExtractExpenses($this->receipt))->handle();

    $this->receipt->refresh();

    expect($this->receipt->status)->toBe(ReceiptStatus::Processed);
    expect($this->receipt->expenses)->toHaveCount(0);
    expect($this->receipt->expenses_count)->toBe(0);
});

it('coerces string types to correct php types', function () {
    AnonymousAgent::fake(fn () => json_encode([
        'articles' => [
            [
                'label' => 'Pain',
                'quantity' => '5',
                'unit_price' => '2.50',
                'category' => 'food',
            ],
        ],
        'total_estimated' => 12.50,
        'currency' => 'MAD',
    ]));

    (new ExtractExpenses($this->receipt))->handle();

    $this->receipt->refresh();

    $expense = $this->receipt->expenses->first();
    expect($expense->quantity)->toBe(5);
    expect((float) $expense->unit_price)->toBe(2.50);
});

it('maps unknown category to Other', function () {
    AnonymousAgent::fake(fn () => json_encode([
        'articles' => [
            [
                'label' => 'Some item',
                'quantity' => 1,
                'unit_price' => 10.00,
                'category' => 'unknown_category',
            ],
        ],
        'total_estimated' => 10.00,
        'currency' => 'MAD',
    ]));

    (new ExtractExpenses($this->receipt))->handle();

    $this->receipt->refresh();

    $expense = $this->receipt->expenses->first();
    expect($expense->category)->toBe(ExpenseCategory::Other);
});

it('handles ai api exception gracefully', function () {
    AnonymousAgent::fake(fn () => throw new Exception('API unreachable'));

    (new ExtractExpenses($this->receipt))->handle();

    $this->receipt->refresh();

    expect($this->receipt->status)->toBe(ReceiptStatus::Failed);
    expect($this->receipt->expenses)->toHaveCount(0);
});

it('dispatches job on receipt submission', function () {
    Queue::fake();

    $this->actingAs($this->user);

    Livewire::test(Create::class)
        ->set('source_text', '3x Lait Danone 12.50')
        ->call('submit');

    Queue::assertPushed(ExtractExpenses::class);
    expect(Receipt::count())->toBe(2); // 1 from beforeEach + 1 new
});
