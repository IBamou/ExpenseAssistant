<?php

namespace App\Models;

use App\Enums\ReceiptStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Receipt extends Model
{
    protected $fillable = [
        'user_id',
        'source_text',
        'status',
        'raw_ai_payload',
        'expenses_count',
    ];

    protected function casts(): array
    {
        return [
            'status' => ReceiptStatus::class,
            'raw_ai_payload' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
