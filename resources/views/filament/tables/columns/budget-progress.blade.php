@php
    $budget = (float) $getRecord()->monthly_budget;
    $spent = \App\Models\Transaction::where('category_id', $getRecord()->id)
        ->where('type', 'expense')
        ->whereMonth('transaction_date', now()->month)
        ->whereYear('transaction_date', now()->year)
        ->get()
        ->sum(fn($t) => (float) $t->amount);
    
    $percentage = ($budget > 0) ? min(($spent / $budget) * 100, 100) : 0;
    
    $color = 'bg-success-600';
    if ($percentage >= 100) $color = 'bg-danger-600';
    elseif ($percentage >= 80) $color = 'bg-warning-500';
@endphp

<div class="w-full min-w-[150px]">
    <div class="flex items-center justify-between mb-1">
        <span class="text-xs font-medium text-gray-700 dark:text-gray-400">
            {{ number_format($percentage, 0) }}%
        </span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700 overflow-hidden">
        <div class="{{ $color }} h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
    </div>
</div>
