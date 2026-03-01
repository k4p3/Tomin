<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between gap-x-3 py-2">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">
                    {{ __('Hola, :name', ['name' => auth()->user()->name]) }} 👋
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Estas viendo la billetera:') }} <span class="font-semibold text-primary-600 uppercase">{{ \Filament\Facades\Filament::getTenant()->name }}</span>
                </p>
            </div>
            <div class="hidden sm:block text-right text-xs text-gray-400">
                <p>{{ now()->translatedFormat('l, d F Y') }}</p>
                <p class="uppercase">{{ \Filament\Facades\Filament::getTenant()->currency }}</p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
