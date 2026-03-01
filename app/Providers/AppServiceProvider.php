<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\Transaction::observe(\App\Observers\TransactionObserver::class);
        \App\Models\Invitation::observe(\App\Observers\InvitationObserver::class);
        \App\Models\Category::observe(\App\Observers\CategoryObserver::class);
        \App\Models\Account::observe(\App\Observers\AccountObserver::class);

        // Forzar locale si hay sesión de usuario
        if ($user = auth()->user()) {
            app()->setLocale($user->locale);
        }
    }
}
