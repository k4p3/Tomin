<x-filament-panels::page>
    <form wire:submit="generateReport">
        {{ $this->form }}
    </form>

    @if($report)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-filament::section>
                <div class="text-sm text-gray-500">{{ __('Total Income') }}</div>
                <div class="text-2xl font-bold text-success-600">${{ number_format($report['current']['total_income'], 2) }}</div>
                <div class="text-xs mt-1 {{ $report['comparison']['income_diff'] >= 0 ? 'text-success-500' : 'text-danger-500' }}">
                    {{ $report['comparison']['income_diff'] >= 0 ? '↑' : '↓' }} 
                    {{ number_format(abs($report['comparison']['income_percentage']), 1) }}% {{ __('vs previous month') }}
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-sm text-gray-500">{{ __('Total Expenses') }}</div>
                <div class="text-2xl font-bold text-danger-600">${{ number_format($report['current']['total_expenses'], 2) }}</div>
                <div class="text-xs mt-1 {{ $report['comparison']['expense_diff'] <= 0 ? 'text-success-500' : 'text-danger-500' }}">
                    {{ $report['comparison']['expense_diff'] >= 0 ? '↑' : '↓' }} 
                    {{ number_format(abs($report['comparison']['expense_percentage']), 1) }}% {{ __('vs previous month') }}
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-sm text-gray-500">{{ __('Net Cash Flow') }}</div>
                <div class="text-2xl font-bold {{ $report['current']['net_flow'] >= 0 ? 'text-success-600' : 'text-danger-600' }}">
                    ${{ number_format($report['current']['net_flow'], 2) }}
                </div>
                <div class="text-xs mt-1 text-gray-400 italic">
                    {{ __('Summary for the selected period') }}
                </div>
            </x-filament::section>
        </div>

        <x-filament::section class="mt-6" heading="{{ __('Expenses by User') }}">
            <div class="space-y-4">
                @foreach($report['expenses_by_user'] as $userId => $userData)
                    <div class="flex justify-between items-center border-b pb-2 dark:border-gray-700">
                        <span class="font-medium">{{ $userData['name'] }}</span>
                        <span class="font-bold">${{ number_format($userData['amount'], 2) }}</span>
                    </div>
                @endforeach
                
                @if(count($report['expenses_by_user']) === 0)
                    <p class="text-gray-500 italic">{{ __('No expense data recorded for this month.') }}</p>
                @endif
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
