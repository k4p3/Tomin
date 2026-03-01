@php
    $total = $getRecord()->total_installments;
    $remaining = $getRecord()->remaining_installments;
    $paid = $total - $remaining;
    $percentage = ($paid / $total) * 100;
@endphp

<div class="w-full">
    <div class="flex items-center justify-between mb-1">
        <span class="text-xs font-medium text-gray-700 dark:text-gray-400">
            {{ $paid }} / {{ $total }} {{ __('meses') }}
        </span>
        <span class="text-xs font-medium text-gray-700 dark:text-gray-400">
            {{ number_format($percentage, 0) }}%
        </span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
        <div class="bg-primary-600 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
    </div>
</div>
