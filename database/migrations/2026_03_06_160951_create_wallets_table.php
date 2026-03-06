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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('currency', 3)->default('NGN'); // Nigerian Naira

            // balance = total money in wallet
            $table->decimal('balance', 15, 2)->default(0.00);

            // frozen_balance = money held in escrow during trades
            // Available = balance - frozen_balance
            $table->decimal('frozen_balance', 15, 2)->default(0.00);

            // Lifetime stats
            $table->decimal('total_earned', 15, 2)->default(0.00);
            $table->decimal('total_spent', 15, 2)->default(0.00);

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // A user can only have one wallet per currency
            $table->unique(['user_id', 'currency']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
