<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        tenancy()->initialize($tenant);
        $request->attributes->set('tenant', $tenant);
        $request->session()->put('tenant_id', $tenant->getTenantKey());

        try {
            return $next($request);
        } finally {
            tenancy()->end();
        }
    }
}
