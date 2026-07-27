<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Module;
use App\Models\Resource;
use App\Models\Tenant;
use App\Models\User;
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

        $this->assertDatabaseHas('users', [
            'username' => 'admincenotos',
            'tenant_id' => 'empresa_demo',
        ]);
        $this->assertSame(3, User::query()->count());

        $this->assertSame('Empresa demo 1', Tenant::query()->find('empresa_demo')?->name);
        $userId = User::query()->where('username', 'testuser')->value('id');

        $this->assertDatabaseHas('user_tenants', [
            'user_id' => $userId,
            'tenant_id' => 'empresa_demo',
            'is_default' => true,
            'active' => true,
        ]);

        $adminId = User::query()->where('username', 'admincenotos')->value('id');

        $this->assertDatabaseHas('user_tenants', [
            'user_id' => $adminId,
            'tenant_id' => 'empresa_demo',
            'is_default' => true,
            'active' => true,
        ]);
        $this->assertDatabaseHas('user_tenants', [
            'user_id' => $adminId,
            'tenant_id' => 'empresa_demo_2',
            'is_default' => false,
            'active' => true,
        ]);
    }

    public function test_seeded_tenants_are_active(): void
    {
        $this->seed();

        $this->assertTrue(Tenant::query()->where('id', 'empresa_demo')->value('active'));
        $this->assertTrue(Tenant::query()->where('id', 'empresa_demo_2')->value('active'));
    }

    public function test_modules_are_linked_to_their_catalogued_resources(): void
    {
        $sales = Module::query()->where('code', 'sales')->firstOrFail();
        $references = Module::query()->where('code', 'references')->firstOrFail();

        $this->assertTrue($sales->active);
        $this->assertTrue($references->active);
        $this->assertEqualsCanonicalizing(
            ['sale_header', 'sale_line'],
            $sales->resources()->pluck('code')->all(),
        );
        $this->assertEqualsCanonicalizing(
            [
                'article',
                'bank',
                'contact',
                'customer',
                'document_type',
                'entity_category',
                'payment_method',
                'seller',
                'supplier',
                'tax',
                'term_payment',
                'vat_exemption',
            ],
            $references->resources()->pluck('code')->all(),
        );
        $this->assertSame(16, Resource::query()->count());
    }
}
