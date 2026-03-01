<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('account_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('merchant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained(); // Autor
            $table->text('amount'); // Encriptado
            $table->enum('type', ['income', 'expense', 'transfer'])->default('expense');
            $table->boolean('is_shared')->default(true);
            $table->text('description')->nullable(); // Encriptado
            $table->date('transaction_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
