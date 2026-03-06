<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // WHY: Laravel already created a users table.
// We need to ADD columns to it, not create a new one.
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Notice: Schema::table (not Schema::create)
            // We're modifying an existing table

            $table->string('username')->unique()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar')->nullable();

            // KYC = "Know Your Customer" - identity verification
            $table->string('kyc_status')->default('unverified');
            $table->string('kyc_document')->nullable();
            $table->timestamp('kyc_verified_at')->nullable();

            // Referral system
            $table->string('referral_code')->unique()->nullable();
            $table->foreignId('referred_by')->nullable()
                ->constrained('users'); // Points to another user

            // Account control
            $table->boolean('is_active')->default(true);
            $table->boolean('is_banned')->default(false);
            $table->string('ban_reason')->nullable();
            $table->boolean('is_admin')->default(false);

            // Transaction PIN (4-digit security)
            $table->string('pin')->nullable();

            $table->string('country_code', 3)->default('NG');
            $table->timestamp('last_active_at')->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove all columns we added (for rollback)
            $table->dropColumn([
                'username', 'phone', 'avatar', 'kyc_status',
                'kyc_document', 'kyc_verified_at', 'referral_code',
                'referred_by', 'is_active', 'is_banned', 'ban_reason',
                'is_admin', 'pin', 'country_code', 'last_active_at',
                'deleted_at',
            ]);
        });
    }
};
