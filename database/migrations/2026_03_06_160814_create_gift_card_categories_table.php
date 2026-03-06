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
    // database/migrations/xxxx_create_gift_card_categories_table.php
    public function up(): void
    {
        Schema::create('gift_card_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "Amazon"
            $table->string('slug')->unique(); // "amazon"
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->decimal('min_value', 10, 2)->default(5.00);
            $table->decimal('max_value', 10, 2)->default(500.00);
            $table->decimal('platform_rate', 5, 2)->default(80.00);
            $table->decimal('commission_rate', 5, 2)->default(5.00);
            $table->boolean('is_active')->default(true);
            $table->boolean('platform_buying')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('denominations')->nullable(); // [25, 50, 100, 200]
            $table->timestamps();
            $table->softDeletes();
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
