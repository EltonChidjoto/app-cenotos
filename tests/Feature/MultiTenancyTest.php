<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiTenancyTest extends TestCase
{
    use RefreshDatabase;

    public function test_central_seed_links_each_user_to_an_existing_tenant(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
            'tenant_id' => 'empresa_demo',
        ]);

        $this->assertDatabaseHas('users', [
            'username' => 'empresa2user',
            'tenant_id' => 'empresa_demo_2',
        ]);

        $this->assertSame('Empresa demo 1', Tenant::query()->find('empresa_demo')?->name);
    }

    public function test_seeded_tenants_are_active(): void
    {
        $this->seed();

        $this->assertTrue(Tenant::query()->where('id', 'empresa_demo')->value('active'));
        $this->assertTrue(Tenant::query()->where('id', 'empresa_demo_2')->value('active'));
    }
}
