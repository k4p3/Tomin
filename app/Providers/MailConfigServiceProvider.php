<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Registra los servicios.
     */
    public function register(): void
    {
        //
    }

    /**
     * Arranca los servicios.
     */
    public function boot(): void
    {
        // Solo intentamos cargar si la tabla de settings existe (evita errores en migraciones iniciales)
        if (app()->runningInConsole() === false && Schema::hasTable('settings')) {
            $settings = Setting::where('key', 'like', 'mail_%')->pluck('value', 'key');

            if ($settings->isNotEmpty()) {
                Config::set('mail.mailers.smtp.host', $settings->get('mail_host'));
                Config::set('mail.mailers.smtp.port', $settings->get('mail_port'));
                Config::set('mail.mailers.smtp.username', $settings->get('mail_username'));
                Config::set('mail.mailers.smtp.password', $settings->get('mail_password'));
                Config::set('mail.mailers.smtp.encryption', $settings->get('mail_encryption'));
                Config::set('mail.from.address', $settings->get('mail_from_address'));
                Config::set('mail.from.name', config('app.name'));
            }
        }
    }
}
