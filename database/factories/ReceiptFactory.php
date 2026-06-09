<?php

namespace Database\Factories;

use App\Enums\ReceiptStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReceiptFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'source_text' => fake()->sentence(20),
            'status' => ReceiptStatus::Pending,
        ];
    }
}
