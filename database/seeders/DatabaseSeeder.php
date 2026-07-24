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
        $tenants = [
            'empresa_demo' => 'Empresa demo 1',
            'empresa_demo_2' => 'Empresa demo 2',
        ];

        foreach ($tenants as $tenantId => $name) {
            Tenant::query()->updateOrCreate(
                ['id' => $tenantId],
                [
                    'name' => $name,
                    'slug' => $tenantId,
                    'active' => true,
                ]
            );
        }

        $users = [
            [
                'name' => 'Test User',
                'username' => 'testuser',
                'email' => 'test@example.com',
                'tenant_id' => 'empresa_1',
            ],
            [
                'name' => 'Empresa 2 User',
                'username' => 'empresa2user',
                'email' => 'empresa2@example.com',
                'tenant_id' => 'empresa_2',
            ],
        ];

        foreach ($users as $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                [
                    ...$user,
                    'password' => 'password',
                ]
            );
        }
    }
}
