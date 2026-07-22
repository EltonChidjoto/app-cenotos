<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TenantService
{
    public function __construct(private readonly TenantQueryCache $queryCache) { }

    public function current(): ?Tenant {
        if (! function_exists('tenancy') || ! tenancy()->initialized) {
            return null;
        }

        return tenancy()->tenant;
    }

    public function requireCurrent(): Tenant {
        return $this->current() ?? abort(403, 'Nenhum tenant foi inicializado.');
    }

    /**
     * Executa uma consulta páginada no contexto do tenant e usa o Redis quando activo.
     *
     * @param class-string<\Illuminate\Database\Eloquent\Model> $modelClass
     */
    public function paginate(string $domain, string $modelClass, Closure $queryFactory, array $filters = [], int $perPage = 10, ?Tenant $tenant = null): LengthAwarePaginator {
        return $this->queryCache->paginate(
            tenant: $tenant ?? $this->requireCurrent(),
            domain: $domain,
            modelClass: $modelClass,
            queryFactory: $queryFactory,
            filters: $filters,
            perPage: $perPage,
        );
    }

    public function cacheMeta(string $domain, array $filters = [], int $perPage = 10, ?Tenant $tenant = null,): array {
        return $this->queryCache->meta(
            tenant: $tenant ?? $this->requireCurrent(),
            domain: $domain,
            filters: $filters,
            perPage: $perPage,
        );
    }

    public function forgetCache(string $domain, ?Tenant $tenant = null): void {
        $tenant ??= $this->current();

        if ($tenant !== null) {
            $this->queryCache->forget($tenant, $domain);
        }
    }
}
