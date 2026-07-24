<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
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

        return view('tenant.dashboard', [
            'tenant' => $tenant,
            'sales' => $sales,
            'salesTotal' => $sales->total(),
            'salesAmount' => (float) $sales->sum('amount'),
            'cacheInfo' => $this->tenantService->cacheMeta('sales', $filters, 10, $tenant),
        ]);
    }
}
