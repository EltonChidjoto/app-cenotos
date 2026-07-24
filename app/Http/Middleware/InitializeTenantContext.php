<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->route('login');
        }

        // The authenticated user's central record is the source of truth.
        // A session value must never be allowed to select another tenant.
        $tenant = $user->tenant;

        abort_if($tenant === null, 403, 'O utilizador não está associado a nenhuma empresa.');
        abort_if(! $tenant->active, 403, 'A empresa deste utilizador está inativa.');

        $initialized = false;

        try {
            tenancy()->initialize($tenant);
            $initialized = true;

            $request->attributes->set('tenant', $tenant);

            return $next($request);
        } finally {
            if ($initialized && tenancy()->initialized) {
                tenancy()->end();
            }
        }
    }
}
