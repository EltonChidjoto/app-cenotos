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
        $tenant = $request->attributes->get('tenant');

        return view('tenant.dashboard', [
            'tenant' => $tenant,
            'sales' => Sale::query()
                ->latest()
                ->limit(10)
                ->get(),
            'salesTotal' => Sale::query()->count(),
            'salesAmount' => (float) Sale::query()->sum('amount'),
        ]);
    }
}
