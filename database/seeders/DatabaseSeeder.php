<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment('production') && ! config('tenancy.seed_demo_data')) {
            $this->command?->warn('Demo seed ignorado em produção. Defina SEED_DEMO_DATA=true para o ativar explicitamente.');

            return;
        }

        $password = config('tenancy.demo_user_password') ?: 'password';

        if (app()->environment('production') && strlen($password) < 12) {
            throw new RuntimeException('DEMO_USER_PASSWORD deve ter pelo menos 12 caracteres em produção.');
        }

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
                'tenant_id' => 'empresa_demo',
                'tenant_ids' => ['empresa_demo'],
            ],
            [
                'name' => 'Empresa 2 User',
                'username' => 'empresa2user',
                'email' => 'empresa2@example.com',
                'tenant_id' => 'empresa_demo_2',
                'tenant_ids' => ['empresa_demo_2'],
            ],
            [
                'name' => 'Cenotos Admin',
                'username' => 'admincenotos',
                'email' => 'admin@cenotos.local',
                'tenant_id' => 'empresa_demo',
                'tenant_ids' => ['empresa_demo', 'empresa_demo_2'],
            ],
        ];

        foreach ($users as $user) {
            $tenantIds = $user['tenant_ids'];
            unset($user['tenant_ids']);

            $userModel = User::query()->updateOrCreate(
                ['email' => $user['email']],
                [
                    ...$user,
                    'password' => $password,
                ]
            );

            $memberships = collect($tenantIds)
                ->mapWithKeys(fn (string $tenantId): array => [
                    $tenantId => [
                        'is_default' => $tenantId === $user['tenant_id'],
                        'active' => true,
                    ],
                ])
                ->all();

            $userModel->tenants()->syncWithoutDetaching($memberships);
        }
    }
}
