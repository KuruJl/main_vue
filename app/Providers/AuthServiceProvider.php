<?php

namespace App\Providers;

use App\Models\Listing;
use App\Policies\ListingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Listing::class => ListingPolicy::class, // <-- ДОБАВЬТЕ ЭТУ СТРОКУ
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}