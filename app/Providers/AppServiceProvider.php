<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




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
