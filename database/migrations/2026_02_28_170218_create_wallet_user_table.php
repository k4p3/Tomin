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
        Schema::create('wallet_user', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('wallet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role')->default('contributor'); // 'owner', 'contributor'
            $table->timestamps();
            
            $table->unique(['wallet_id', 'user_id']); // Un usuario no puede estar dos veces en la misma billetera
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_user');
    }
};
