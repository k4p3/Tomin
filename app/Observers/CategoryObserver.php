<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class CategoryObserver
{
    /**
     * Se ejecuta cuando se actualiza una categoría.
     */
    public function updated(Category $category): void
    {
        // Solo registramos si cambió el presupuesto mensual
        if ($category->wasChanged('monthly_budget')) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'budget_updated',
                'model_type' => Category::class,
                'model_id' => $category->id,
                'old_values' => ['monthly_budget' => $category->getOriginal('monthly_budget')],
                'new_values' => ['monthly_budget' => $category->monthly_budget],
                'ip_address' => request()->ip(),
            ]);
        }
    }
}
