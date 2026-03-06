<?php

namespace App\Models;

use App\Enums\KycStatus;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    // HasApiTokens: Allows this user to create API tokens (for Sanctum)
    // Notifiable: Can receive notifications (email, SMS, etc.)
    // SoftDeletes: Soft delete support
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'username', 'email', 'phone', 'password', 'avatar',
        'kyc_status', 'referral_code', 'referred_by', 'is_active',
        'is_banned', 'ban_reason', 'is_admin', 'pin', 'country_code',
        'last_active_at',
    ];

    protected $hidden = ['password', 'remember_token', 'pin'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',  // Auto-hashes when setting
            'pin' => 'hashed',       // Auto-hashes when setting
            'is_active' => 'boolean',
            'is_banned' => 'boolean',
            'is_admin' => 'boolean',
        ];
    }

    // ─── FILAMENT: Who can access the admin panel? ───
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }

    // ─── RELATIONSHIPS ───
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function giftCards()
    {
        return $this->hasMany(GiftCard::class);
    }

    public function sellerTrades()
    {
        return $this->hasMany(Trade::class, 'seller_id');
    }

    public function buyerTrades()
    {
        return $this->hasMany(Trade::class, 'buyer_id');
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    // ─── HELPERS ───
    public function getDefaultWallet(): Wallet
    {
        return $this->wallet ?? $this->wallets()->create([
            'currency' => 'NGN',
            'balance' => 0,
        ]);
    }

    // ─── AUTO-ACTIONS ───
    protected static function booted(): void
    {
        // When a user is created, auto-generate referral code + wallet
        static::creating(function (User $user) {
            if (empty($user->referral_code)) {
                $user->referral_code = strtoupper(substr(md5(uniqid()), 0, 8));
            }
        });

        static::created(function (User $user) {
            // Auto-create a wallet for every new user
            $user->wallet()->create([
                'currency' => 'NGN',
                'balance' => 0,
            ]);
        });
    }
}
