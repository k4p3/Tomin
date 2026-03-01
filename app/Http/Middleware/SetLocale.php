<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Number;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Maneja la solicitud entrante.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($user = $request->user()) {
            // 1. Aplicar Idioma
            $locale = $user->locale ?? config('app.locale');
            App::setLocale($locale);

            // 2. Aplicar Formato de Moneda/Número
            // Laravel usa locales de intl para determinar los separadores.
            // comma_dot (1,234.56) -> Usamos en_US o es_MX
            // dot_comma (1.234,56) -> Usamos es_ES o de_DE
            $numberLocale = ($user->number_format === 'comma_dot') ? 'en_US' : 'es_ES';
            Number::useLocale($numberLocale);
        }

        return $next($request);
    }
}
