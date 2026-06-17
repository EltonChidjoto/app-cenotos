<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Sale;
use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Sale::query()->firstOrCreate(
            ['reference' => 'V-1001'],
            [
                'customer_name' => 'Cliente Demo',
                'amount' => 12500.00,
                'status' => 'paid',
                'sold_at' => now(),
            ]
        );
    }
}
