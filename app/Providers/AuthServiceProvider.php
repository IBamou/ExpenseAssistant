<?php

namespace App\Providers;

use App\Models\Receipt;
use App\Policies\ReceiptPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Receipt::class => ReceiptPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
