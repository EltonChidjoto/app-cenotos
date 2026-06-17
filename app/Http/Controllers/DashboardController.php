<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redis;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        // $keys = Redis::connection()->keys('*');
        // dd($keys);
        // $value = Redis::connection()->get("cenotos-database-cenotos-cache-9AuTyM8JiIIlBYdffFRkHaH56evuRJm2zbGe0ocn");
        // dd($value);
        $tenant = $request->attributes->get('tenant');

        return view('tenant.dashboard', [
            'tenant' => $tenant,
            'sales' => Sale::query()
                ->latest()
                ->get(),
            'salesTotal' => Sale::query()->count(),
            'salesAmount' => (float) Sale::query()->sum('amount'),
        ]);
    }
}
