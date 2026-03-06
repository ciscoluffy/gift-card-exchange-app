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
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // Public-facing ID (not sequential)

            // WHO listed this card
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // WHAT brand (Amazon, iTunes, etc.)
            $table->foreignId('category_id')
                ->constrained('gift_card_categories');

            // Card credentials (will be encrypted in the model)
            $table->string('card_number')->nullable();
            $table->string('card_pin')->nullable();
            $table->string('card_code')->nullable();

            // Values
            $table->decimal('face_value', 10, 2); // What the card says ($100)
            $table->decimal('selling_price', 10, 2); // What seller wants (₦45,000)
            $table->string('currency', 3)->default('USD');

            // Current state of this card
            $table->string('status')->default('draft');

            // How are they selling?
            // 'sell' = P2P marketplace
            // 'platform_sell' = selling directly to the platform
            $table->string('trade_type')->default('sell');

            $table->text('description')->nullable();

            // Verification images (stored as JSON array of file paths)
            $table->json('images')->nullable();

            // Admin verification
            $table->string('verification_status')->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('listed_at')->nullable();

            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for fast queries
            $table->index(['status', 'trade_type']);
            $table->index(['category_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_cards');
    }
};
