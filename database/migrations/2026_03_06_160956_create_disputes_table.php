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
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('reference')->unique(); // DSP-ABCDEF

            $table->foreignId('trade_id')->constrained();
            $table->foreignId('opened_by')->constrained('users');
            $table->foreignId('assigned_to')->nullable()->constrained('users');

            $table->string('reason');          // Short reason
            $table->text('description');       // Detailed explanation
            $table->json('evidence')->nullable(); // Screenshots

            $table->string('status')->default('open');
            $table->text('resolution_notes')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users');
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
