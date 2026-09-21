<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response {
        $tenant = $this->tenantService->requireCurrent();
        $page = max(1, min($request->integer('page', 1), 10000));
        $filters = ['page' => $page];
        
        $sales = $this->tenantService->paginate(
            domain: 'sales',
            modelClass: Sale::class,
            queryFactory: fn () => Sale::query()->latest(),
            filters: $filters,
            perPage: 10,
            tenant: $tenant
        );

        return Inertia::render('Tenant/Dashboard', [
            'tenant' => [
                'id' => $tenant->getTenantKey(),
                'name' => $tenant->displayName(),
                'databaseName' => $tenant->getInternal('db_name'),
            ],
            'sales' => $sales,
            'salesTotal' => $sales->total(),
            'salesAmount' => (float) $sales->sum('amount'),
            'cacheInfo' => $this->tenantService->cacheMeta('sales', $filters, 10, $tenant),
        ]);
    }
}
