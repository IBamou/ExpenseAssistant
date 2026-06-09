<?php

namespace App\Jobs;

use App\Enums\ExpenseCategory;
use App\Enums\ReceiptStatus;
use App\Models\Receipt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\AnonymousAgent;

class ExtractExpenses implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Receipt $receipt
    ) {}

    public function handle(): void
    {
        try {
            $agent = new AnonymousAgent(
                instructions: <<<'PROMPT'
You are an expense extraction assistant. Extract structured expense data from supplier receipt text and return ONLY valid JSON.

The receipt text may be in Darija, French, Arabic, or mixed. It may contain abbreviations, scribbles, or informal writing.

CRITICAL RULES:
- ALL numeric values MUST be actual computed numbers, NEVER formulas or expressions.
- For example: if the receipt says "3x Lait 12.50", the unit_price must be 12.50 (not "12.50*3").
- Return ONLY the JSON object. No markdown, no explanations, no extra text.

{
    "articles": [
        {
            "label": "Item name",
            "quantity": 1,
            "unit_price": 0.00,
            "category": "food"
        }
    ],
    "currency": "MAD"
}

Field rules:
- quantity: integer (default 1 if not specified)
- unit_price: actual number in MAD for ONE unit
- category: exactly one of "food", "drinks", "hygiene", "cleaning", "other"
- currency: always "MAD"
PROMPT,
                messages: [],
                tools: [],
            );

            $response = $agent->prompt(
                prompt: $this->receipt->source_text,
                provider: config('ai.default'),
                model: config('ai.model'),
                timeout: (int) config('ai.timeout', 30),
            );

            $raw = trim((string) $response);
            $raw = preg_replace('/^```json\s*/i', '', $raw);
            $raw = preg_replace('/\s*```$/i', '', $raw);

            $this->receipt->update([
                'raw_ai_payload' => ['raw_response' => $raw],
            ]);

            $data = json_decode($raw, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Invalid JSON from AI: '.json_last_error_msg());
            }

            if (! isset($data['articles']) || ! is_array($data['articles'])) {
                throw new \RuntimeException('AI response missing articles array');
            }

            DB::transaction(function () use ($data) {
                $expenses = [];

                foreach ($data['articles'] as $article) {
                    $category = $this->resolveCategory($article['category'] ?? '');

                    $expenses[] = [
                        'receipt_id' => $this->receipt->id,
                        'label' => $article['label'] ?? 'Unknown item',
                        'quantity' => (int) ($article['quantity'] ?? 1),
                        'unit_price' => (float) ($article['unit_price'] ?? 0),
                        'category' => $category->value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                $this->receipt->expenses()->insert($expenses);

                $this->receipt->update([
                    'status' => ReceiptStatus::Processed,
                    'raw_ai_payload' => $data,
                    'expenses_count' => count($expenses),
                ]);
            });
        } catch (\Throwable $e) {
            report($e);

            $this->receipt->update([
                'status' => ReceiptStatus::Failed,
            ]);
        }
    }

    private function resolveCategory(string $category): ExpenseCategory
    {
        return match (strtolower(trim($category))) {
            'food' => ExpenseCategory::Food,
            'drinks' => ExpenseCategory::Drinks,
            'hygiene' => ExpenseCategory::Hygiene,
            'cleaning' => ExpenseCategory::Cleaning,
            default => ExpenseCategory::Other,
        };
    }
}
