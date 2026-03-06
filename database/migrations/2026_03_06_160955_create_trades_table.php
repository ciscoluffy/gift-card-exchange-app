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
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('reference')->unique(); // TRD-ABCD1234

            // What card is being traded
            $table->foreignId('gift_card_id')->constrained();

            // The two parties
            $table->foreignId('seller_id')->constrained('users');
            $table->foreignId('buyer_id')->nullable()->constrained('users');
            // buyer_id is nullable because platform trades have no buyer user

            // Is the PLATFORM buying this card?
            $table->boolean('is_platform_trade')->default(false);

            // Money
            $table->decimal('amount', 15, 2); // Total trade value
            $table->decimal('commission', 15, 2)->default(0); // Platform's cut
            $table->decimal('seller_receives', 15, 2); // amount - commission

            $table->string('currency', 3)->default('NGN');
            $table->string('status')->default('pending');

            // If cancelled
            $table->text('cancellation_reason')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users');

            // Timeline
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
