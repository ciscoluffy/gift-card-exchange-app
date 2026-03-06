<?php
// database/migrations/xxxx_create_gift_card_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * up() = what happens when you RUN the migration
     * This creates the table
     */
    public function up(): void
    {
        Schema::create('gift_card_categories', function (Blueprint $table) {
            // Every table needs an ID
            $table->id();

            // The brand name: "Amazon", "iTunes", "Steam"
            $table->string('name');

            // URL-friendly version: "amazon", "itunes", "steam"
            $table->string('slug')->unique();

            // Optional description of this brand
            $table->text('description')->nullable();

            // Path to brand logo image
            $table->string('logo')->nullable();

            // What currency are these cards in?
            $table->string('currency', 3)->default('USD');

            // Card value limits
            $table->decimal('min_value', 10, 2)->default(5.00);
            $table->decimal('max_value', 10, 2)->default(500.00);

            // What % does the platform pay when BUYING cards?
            // e.g., 80 means platform pays 80% of face value
            $table->decimal('platform_rate', 5, 2)->default(80.00);

            // What % commission on P2P trades?
            // e.g., 5 means platform takes 5% cut
            $table->decimal('commission_rate', 5, 2)->default(5.00);

            // Is this category visible to users?
            $table->boolean('is_active')->default(true);

            // Does the platform buy this brand directly?
            $table->boolean('platform_buying')->default(true);

            // For ordering in the UI
            $table->integer('sort_order')->default(0);

            // Auto timestamps: created_at, updated_at
            $table->timestamps();
        });
    }

    /**
     * down() = what happens when you ROLLBACK
     * This removes the table
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_card_categories');
    }
};
