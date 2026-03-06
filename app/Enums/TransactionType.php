<?php

namespace App\Enums;

enum TransactionType: string
{
    case DEPOSIT = 'deposit';                   // User added money
    case WITHDRAWAL = 'withdrawal';             // User took money out
    case PURCHASE = 'purchase';                 // Bought a gift card
    case SALE = 'sale';                         // Sold a gift card
    case COMMISSION = 'commission';             // Platform's cut
    case REFUND = 'refund';                     // Money returned
    case PLATFORM_PURCHASE = 'platform_purchase'; // Platform bought user's card

    // Is this type adding money to the wallet?
    public function isCredit(): bool
    {
        return in_array($this, [
            self::DEPOSIT,
            self::SALE,
            self::REFUND,
            self::PLATFORM_PURCHASE,
        ]);
    }
}
