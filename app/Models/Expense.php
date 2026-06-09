<?php

namespace App\Models;

use App\Enums\ExpenseCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'receipt_id',
        'label',
        'quantity',
        'unit_price',
        'category',
    ];

    protected function casts(): array
    {
        return [
            'category' => ExpenseCategory::class,
            'unit_price' => 'decimal:2',
        ];
    }

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(Receipt::class);
    }
}
