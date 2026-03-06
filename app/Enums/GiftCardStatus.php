<?php

namespace App\Enums;

// PHP 8.1 Enums - think of them as a fixed set of allowed values
// Instead of using magic strings like 'listed', 'sold' everywhere,
// we use GiftCardStatus::LISTED, GiftCardStatus::SOLD

enum GiftCardStatus: string
{
    // The lifecycle of a gift card on our platform:
    case DRAFT = 'draft';                           // Just created, not submitted
    case PENDING_VERIFICATION = 'pending_verification'; // Waiting for admin to check
    case VERIFIED = 'verified';                     // Admin confirmed it's real
    case REJECTED = 'rejected';                     // Admin said it's fake/invalid
    case LISTED = 'listed';                         // Live on marketplace
    case SOLD = 'sold';                             // Someone bought it
    case EXPIRED = 'expired';                       // Too old, removed

    // Human-readable label for the admin panel
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PENDING_VERIFICATION => 'Pending Verification',
            self::VERIFIED => 'Verified',
            self::REJECTED => 'Rejected',
            self::LISTED => 'Listed',
            self::SOLD => 'Sold',
            self::EXPIRED => 'Expired',
        };
    }

    // Color for badges in Filament admin
    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::PENDING_VERIFICATION => 'warning',   // Yellow
            self::VERIFIED => 'info',                   // Blue
            self::REJECTED => 'danger',                 // Red
            self::LISTED => 'success',                  // Green
            self::SOLD => 'primary',                    // Purple
            self::EXPIRED => 'gray',
        };
    }
}
