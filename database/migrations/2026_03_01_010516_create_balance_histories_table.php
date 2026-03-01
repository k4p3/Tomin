<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('balance_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('wallet_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_balance', 15, 2);
            $table->date('snapshot_date');
            $table->timestamps();
            
            $table->unique(['wallet_id', 'snapshot_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balance_histories');
    }
};
