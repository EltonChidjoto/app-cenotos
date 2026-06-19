<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\TenantSalesCache;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request, TenantSalesCache $salesCache): View
    {
        $tenant = $request->attributes->get('tenant');
        $sales = $salesCache->paginate($tenant, $request->query(), 10);

        return view('tenant.dashboard', [
            'tenant' => $tenant,
            'sales' => $sales,
            'salesTotal' => $sales->total(),
            'salesAmount' => (float) $sales->sum('amount'),
            'cacheInfo' => $salesCache->meta($tenant, $request->query(), 10),
        ]);
    }
}
