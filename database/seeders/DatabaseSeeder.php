<?php

namespace Database\Seeders;

use App\Models\GiftCardCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@giftcardx.com',
            'password' => 'password',  // Auto-hashed by cast
            'email_verified_at' => now(),
            'is_admin' => true,
            'kyc_status' => 'verified',
        ]);

        // Create test users
        User::create([
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        // Create gift card brands
        $brands = [
            ['name' => 'Amazon', 'slug' => 'amazon', 'platform_rate' => 80, 'commission_rate' => 5],
            ['name' => 'iTunes', 'slug' => 'itunes', 'platform_rate' => 75, 'commission_rate' => 5],
            ['name' => 'Steam', 'slug' => 'steam', 'platform_rate' => 70, 'commission_rate' => 5],
            ['name' => 'Google Play', 'slug' => 'google-play', 'platform_rate' => 70, 'commission_rate' => 5],
        ];

        foreach ($brands as $i => $brand) {
            GiftCardCategory::create(array_merge($brand, [
                'currency' => 'USD',
                'is_active' => true,
                'sort_order' => $i,
            ]));
        }
    }
}
