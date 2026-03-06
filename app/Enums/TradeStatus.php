<?php

namespace App\Enums;

enum TradeStatus: string
{
    // The lifecycle of a trade:
    case PENDING = 'pending';       // Buyer initiated, waiting for seller
    case ACCEPTED = 'accepted';     // Seller agreed to the trade
    case PROCESSING = 'processing'; // Being processed (verification, etc.)
    case COMPLETED = 'completed';   // Done! Money released to seller
    case CANCELLED = 'cancelled';   // Someone cancelled
    case DISPUTED = 'disputed';     // There's a problem, admin needs to decide
    case REFUNDED = 'refunded';     // Money returned to buyer

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::ACCEPTED, self::PROCESSING => 'info',
            self::COMPLETED => 'success',
            self::CANCELLED => 'gray',
            self::DISPUTED => 'danger',
            self::REFUNDED => 'warning',
        };
    }
}
