<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Form;
use Filament\Schemas\Schema;

class Login extends BaseLogin
{
    /**
     * Podemos personalizar el título o layout aquí si es necesario.
     */
    public function getHeading(): string
    {
        return __('Welcome Back');
    }

    public function getSubheading(): string | \Illuminate\Contracts\Support\Htmlable | null
    {
        return __('Login to your shared financial space.');
    }
}
