<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Finanzas Inteligentes</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Figtree', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #fef3c7 0%, #fffbeb 100%); }
    </style>
</head>
<body class="antialiased gradient-bg min-h-screen">
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen selection:bg-amber-500 selection:text-white">
        <!-- Navegación Superior -->
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10 w-full flex justify-between items-center px-10">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-2xl font-bold text-gray-900 tracking-tight">{{ config('app.name') }}</span>
            </div>

            <div>
                @auth
                    <a href="{{ url('/admin') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-amber-500">Dashboard</a>
                @else
                    <a href="{{ route('filament.admin.auth.login') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-amber-500">Entrar</a>
                    @if (Route::has('filament.admin.auth.register'))
                        <a href="{{ route('filament.admin.auth.register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-amber-500">Registrarse</a>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="max-w-7xl mx-auto p-6 lg:p-8 mt-20 sm:mt-0">
            <div class="text-center">
                <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 mb-6 tracking-tight">
                    Controla tus finanzas, <br><span class="text-amber-600">junto a los que amas.</span>
                </h1>
                <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                    La plataforma SaaS diseñada para gestionar billeteras compartidas, MSI y automatización de gastos con seguridad de grado bancario.
                </p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('filament.admin.auth.login') }}" class="px-8 py-4 bg-gray-900 text-white font-bold rounded-xl shadow-xl hover:bg-gray-800 transition-all transform hover:-translate-y-1">
                        Comenzar Ahora
                    </a>
                    <a href="#features" class="px-8 py-4 bg-white text-gray-900 font-bold rounded-xl shadow-md border border-gray-100 hover:bg-gray-50 transition-all">
                        Saber Más
                    </a>
                </div>
            </div>

            <!-- Sección de Características -->
            <div id="features" class="mt-24 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Billeteras Compartidas</h3>
                    <p class="text-gray-500">Invita a tu pareja o socios. Gestionen saldos conjuntos manteniendo la privacidad en lo que decidan.</p>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Seguridad Máxima</h3>
                    <p class="text-gray-500">Tus montos y descripciones están encriptados en la base de datos. Solo tú y tus invitados autorizados pueden verlos.</p>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Automatización Total</h3>
                    <p class="text-gray-500">Cargos recurrentes y Meses Sin Intereses automáticos. El sistema registra tus mensualidades por ti cada mes.</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-24 text-center text-gray-400 text-sm">
                &copy; {{ date('Y') }} {{ config('app.name') }} SaaS. Hecho con Laravel 12 y Filament v5.
            </div>
        </div>
    </div>
</body>
</html>
