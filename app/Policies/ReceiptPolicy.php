<?php

namespace App\Policies;

use App\Models\Receipt;
use App\Models\User;

class ReceiptPolicy
{
    public function view(User $user, Receipt $receipt): bool
    {
        return $user->id === $receipt->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user, Receipt $receipt): bool
    {
        return $user->id === $receipt->user_id;
    }
}
