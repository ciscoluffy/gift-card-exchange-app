<?php

namespace App\Models;

use App\Enums\GiftCardStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class GiftCard extends Model
{
    // SoftDeletes: instead of actually deleting, sets deleted_at timestamp
    // The card disappears from queries but stays in the database
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'user_id', 'category_id', 'card_number', 'card_pin',
        'card_code', 'face_value', 'selling_price', 'currency', 'status',
        'trade_type', 'description', 'images', 'verification_status',
        'rejection_reason', 'verified_by', 'verified_at', 'listed_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'face_value' => 'decimal:2',
            'selling_price' => 'decimal:2',

            // This is powerful: Laravel auto-converts the string 'listed'
            // to the GiftCardStatus::LISTED enum when reading from DB
            'status' => GiftCardStatus::class,

            // JSON columns auto-decode to arrays
            'images' => 'array',
            'metadata' => 'array',

            // Dates auto-convert to Carbon instances
            'verified_at' => 'datetime',
            'listed_at' => 'datetime',

            // ENCRYPTED: card_pin and card_code are stored encrypted in DB
            // Laravel auto-encrypts when saving, auto-decrypts when reading
            'card_pin' => 'encrypted',
            'card_code' => 'encrypted',
        ];
    }

    // ─── RELATIONSHIPS ───

    // "This gift card BELONGS TO a user" (the seller)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // "This gift card BELONGS TO a category" (Amazon, iTunes, etc.)
    public function category(): BelongsTo
    {
        return $this->belongsTo(GiftCardCategory::class, 'category_id');
    }

    // "This gift card HAS ONE trade" (when sold)
    public function trade(): HasOne
    {
        return $this->hasOne(Trade::class);
    }

    // ─── SCOPES (Reusable query filters) ───

    // GiftCard::listed()->get() → only listed cards
    public function scopeListed($query)
    {
        return $query->where('status', GiftCardStatus::LISTED);
    }

    // GiftCard::pendingVerification()->get()
    public function scopePendingVerification($query)
    {
        return $query->where('status', GiftCardStatus::PENDING_VERIFICATION);
    }

    // ─── HELPERS ───

    public function getDiscountPercentage(): float
    {
        if ($this->face_value <= 0) return 0;
        return round((1 - ($this->selling_price / $this->face_value)) * 100, 1);
    }

    public function markAsListed(): void
    {
        $this->update([
            'status' => GiftCardStatus::LISTED,
            'listed_at' => now(),
        ]);
    }

    // ─── BOOT (Auto-actions) ───

    protected static function booted(): void
    {
        // Automatically generate UUID when creating a new card
        static::creating(function (GiftCard $card) {
            $card->uuid = $card->uuid ?? (string) Str::uuid();
        });
    }
}
