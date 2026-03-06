<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Wallet extends Model
{
    protected $fillable = [
        'user_id', 'currency', 'balance', 'frozen_balance',
        'total_earned', 'total_spent', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'decimal:2',
            'frozen_balance' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Available balance = total balance minus frozen (escrow) funds
     */
    public function getAvailableBalance(): float
    {
        return max(0, $this->balance - $this->frozen_balance);
    }

    /**
     * Add money to the wallet
     *
     * DB::transaction ensures that if ANYTHING fails inside,
     * ALL changes are rolled back. No partial updates.
     *
     * lockForUpdate() prevents two requests from reading the same
     * balance at the same time (race condition protection)
     */
    public function credit(float $amount, string $type, string $description = ''): WalletTransaction
    {
        return DB::transaction(function () use ($amount, $type, $description) {
            // Lock this wallet row so no one else can read it
            $this->lockForUpdate();

            $balanceBefore = $this->balance;

            // Update the wallet balance
            $this->increment('balance', $amount);
            $this->increment('total_earned', $amount);

            // Create a transaction record (audit trail)
            return $this->transactions()->create([
                'reference' => (string) Str::uuid(),
                'user_id' => $this->user_id,
                'type' => $type,
                'amount' => $amount,       // Positive = credit
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceBefore + $amount,
                'description' => $description,
            ]);
        });
    }

    /**
     * Remove money from the wallet
     */
    public function debit(float $amount, string $type, string $description = '', float $fee = 0): WalletTransaction
    {
        return DB::transaction(function () use ($amount, $type, $description, $fee) {
            $this->lockForUpdate();

            $totalDebit = $amount + $fee;

            // Check if user has enough money
            if ($this->getAvailableBalance() < $totalDebit) {
                throw new \Exception('Insufficient balance');
            }

            $balanceBefore = $this->balance;
            $this->decrement('balance', $totalDebit);
            $this->increment('total_spent', $totalDebit);

            return $this->transactions()->create([
                'reference' => (string) Str::uuid(),
                'user_id' => $this->user_id,
                'type' => $type,
                'amount' => -$totalDebit,  // Negative = debit
                'fee' => $fee,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceBefore - $totalDebit,
                'description' => $description,
            ]);
        });
    }

    /**
     * Freeze funds (hold in escrow during a trade)
     * The money is still in the wallet but can't be spent
     */
    public function freeze(float $amount): void
    {
        DB::transaction(function () use ($amount) {
            $this->lockForUpdate();
            if ($this->getAvailableBalance() < $amount) {
                throw new \Exception('Insufficient balance to freeze');
            }
            $this->increment('frozen_balance', $amount);
        });
    }

    /**
     * Unfreeze funds (release from escrow)
     */
    public function unfreeze(float $amount): void
    {
        DB::transaction(function () use ($amount) {
            $this->lockForUpdate();
            $this->decrement('frozen_balance', min($amount, $this->frozen_balance));
        });
    }
}
