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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();

            // Unique reference like "a1b2c3d4-..." for tracking
            $table->uuid('reference')->unique();

            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // What type: deposit, withdrawal, purchase, sale, etc.
            $table->string('type');

            // POSITIVE = credit (money in), NEGATIVE = debit (money out)
            $table->decimal('amount', 15, 2);
            $table->decimal('fee', 15, 2)->default(0.00);

            // Snapshot of balance before and after this transaction
            // This creates an audit trail
            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);

            $table->string('status')->default('completed');
            $table->string('description')->nullable();

            // Polymorphic relation: what caused this transaction?
            // Could be a Trade, Payout, or anything else
            $table->nullableMorphs('transactable');

            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
