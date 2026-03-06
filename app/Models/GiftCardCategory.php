<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// By convention, model "GiftCardCategory" maps to table "gift_card_categories"
// Laravel auto-converts: PascalCase → snake_case + plural

class GiftCardCategory extends Model
{
    // ─── FILLABLE ───
    // Which columns can be mass-assigned (security feature)
    // Without this, GiftCardCategory::create([...]) would fail
    protected $fillable = [
        'name', 'slug', 'description', 'logo', 'currency',
        'min_value', 'max_value', 'platform_rate', 'commission_rate',
        'is_active', 'platform_buying', 'sort_order',
    ];

    // ─── CASTS ───
    // Tell Laravel how to convert database values to PHP types
    protected function casts(): array
    {
        return [
            'min_value' => 'decimal:2',    // String "5.00" → float 5.00
            'max_value' => 'decimal:2',
            'is_active' => 'boolean',       // 1/0 → true/false
            'platform_buying' => 'boolean',
        ];
    }

    // ─── RELATIONSHIPS ───
    // "A category HAS MANY gift cards"
    public function giftCards()
    {
        return $this->hasMany(GiftCard::class, 'category_id');
    }

    // ─── HELPER METHODS ───
    // Calculate what platform pays for a card
    public function getPlatformBuyPrice(float $faceValue): float
    {
        return round($faceValue * ($this->platform_rate / 100), 2);
    }
}
