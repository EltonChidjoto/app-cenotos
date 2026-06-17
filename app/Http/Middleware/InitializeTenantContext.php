<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Sale;
use App\Models\Tenant;
use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $tenantId = $request->session()->get('tenant_id') ?: $request->user()?->tenant_id;

        if (! $tenantId) {
            abort(403, 'O utilizador não está associado a nenhuma empresa.');
        }

        $tenant = Tenant::query()->findOrFail($tenantId);

        $this->ensureTenantDatabaseExists($tenant);

        tenancy()->initialize($tenant);
        $this->ensureTenantSalesTableAndSeed();
        $request->attributes->set('tenant', $tenant);
        $request->session()->put('tenant_id', $tenant->getTenantKey());

        try {
            return $next($request);
        } finally {
            tenancy()->end();
        }
    }

    private function ensureTenantDatabaseExists(Tenant $tenant): void
    {
        $databaseName = $tenant->database()->getName();
        $databaseManager = $tenant->database()->manager();

        if ($databaseManager->databaseExists($databaseName)) {
            $this->migrateAndSeedTenant($tenant);
            return;
        }

        $tenant->database()->makeCredentials();
        $databaseManager->createDatabase($tenant);

        $this->migrateAndSeedTenant($tenant);
    }

    private function migrateAndSeedTenant(Tenant $tenant): void
    {
        Artisan::call('tenants:migrate', [
            '--tenants' => [$tenant->getTenantKey()],
        ]);
    }

    private function ensureTenantSalesTableAndSeed(): void
    {
        if (! Schema::hasTable('sales')) {
            Schema::create('sales', function (Blueprint $table): void {
                $table->id();
                $table->string('reference')->unique();
                $table->string('customer_name');
                $table->decimal('amount', 12, 2);
                $table->string('status')->default('paid');
                $table->timestamp('sold_at')->nullable();
                $table->timestamps();
            });
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
