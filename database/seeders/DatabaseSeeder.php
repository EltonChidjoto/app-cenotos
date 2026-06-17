<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $empresa1 = Tenant::query()->firstOrCreate(
            ['id' => 'empresa_1'],
            [
                'name' => 'Empresa 1',
                'slug' => 'empresa_1',
                'active' => true,
            ]
        );

        $empresa2 = Tenant::query()->firstOrCreate(
            ['id' => 'empresa_2'],
            [
                'name' => 'Empresa 2',
                'slug' => 'empresa_2',
                'active' => true,
            ]
        );

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'tenant_id' => $empresa1->getKey(),
        ]);

        User::factory()->create([
            'name' => 'Empresa 2 User',
            'email' => 'empresa2@example.com',
            'tenant_id' => $empresa2->getKey(),
        ]);
    }
}
