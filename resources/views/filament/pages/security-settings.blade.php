<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            {{ __('Two-Factor Authentication') }}
        </x-slot>
        
        <div class="max-w-xl">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                {{ __('Two-factor authentication adds an additional layer of security to your account by requiring more than just a password to log in.') }}
            </p>
            
            <div class="flex items-center gap-x-3">
                @if(auth()->user()->two_factor_confirmed_at)
                    <div style="width: 24px; height: 24px; min-width: 24px;" class="text-success-600">
                        <x-heroicon-m-check-badge />
                    </div>
                    <span class="text-sm font-bold text-success-600">
                        {{ __('You have enabled two-factor authentication.') }}
                    </span>
                @else
                    <div style="width: 24px; height: 24px; min-width: 24px;" class="text-danger-600">
                        <x-heroicon-m-x-circle />
                    </div>
                    <span class="text-sm font-bold text-danger-600">
                        {{ __('You have not enabled two-factor authentication.') }}
                    </span>
                @endif
            </div>
        </div>
    </x-filament::section>

    <x-filament::section heading="{{ __('Active Sessions') }}" description="{{ __('Manage and logout your active sessions on other browsers and devices.') }}">
        <div class="overflow-hidden">
            {{ $this->table }}
        </div>
    </x-filament::section>
</x-filament-panels::page>
