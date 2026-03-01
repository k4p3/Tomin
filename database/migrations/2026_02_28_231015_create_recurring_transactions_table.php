<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recurring_transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('wallet_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('account_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('merchant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained(); 
            
            $table->text('amount'); 
            $table->enum('type', ['income', 'expense'])->default('expense');
            $table->text('description'); 
            
            $table->tinyInteger('day_of_month')->unsigned(); 
            $table->date('last_processed_at')->nullable(); 
            $table->boolean('is_active')->default(true);
            $table->boolean('is_shared')->default(true);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recurring_transactions');
    }
};
