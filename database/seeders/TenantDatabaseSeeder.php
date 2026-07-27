<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Sale;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TenantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('sales')) {
            $this->command?->warn('Dados demo de vendas ignorados: o módulo sales ainda não está instalado.');

            return;
        }

        $sales = [
            ['reference' => 'V-1001', 'customer_name' => 'Cliente Demo 1', 'amount' => 12500.00, 'status' => 'paid'],
            ['reference' => 'V-1002', 'customer_name' => 'Cliente Demo 2', 'amount' => 9800.50, 'status' => 'paid'],
            ['reference' => 'V-1003', 'customer_name' => 'Cliente Demo 3', 'amount' => 4300.00, 'status' => 'pending'],
            ['reference' => 'V-1004', 'customer_name' => 'Cliente Demo 4', 'amount' => 15650.75, 'status' => 'paid'],
            ['reference' => 'V-1005', 'customer_name' => 'Cliente Demo 5', 'amount' => 2100.00, 'status' => 'canceled'],
            ['reference' => 'V-1006', 'customer_name' => 'Cliente Demo 6', 'amount' => 7600.25, 'status' => 'paid'],
            ['reference' => 'V-1007', 'customer_name' => 'Cliente Demo 7', 'amount' => 3250.00, 'status' => 'pending'],
            ['reference' => 'V-1008', 'customer_name' => 'Cliente Demo 8', 'amount' => 18990.90, 'status' => 'paid'],
            ['reference' => 'V-1009', 'customer_name' => 'Cliente Demo 9', 'amount' => 540.00, 'status' => 'paid'],
            ['reference' => 'V-1010', 'customer_name' => 'Cliente Demo 10', 'amount' => 1120.00, 'status' => 'pending'],
        ];

        foreach ($sales as $index => $sale) {
            Sale::query()->firstOrCreate(
                ['reference' => $sale['reference']],
                [
                    'customer_name' => $sale['customer_name'],
                    'amount' => $sale['amount'],
                    'status' => $sale['status'],
                    'sold_at' => now()->subDays($index),
                ]
            );
        }
    }
}
