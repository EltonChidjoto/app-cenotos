<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Sale;
use App\Models\Tenant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TenantSalesCache
{
    public function __construct(
        private readonly TenantQueryCache $queryCache,
    ) {
    }

    public function paginate(Tenant $tenant, array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->queryCache->paginate(
            tenant: $tenant,
            domain: 'sales',
            queryFactory: fn () => Sale::query()->latest(),
            filters: $filters,
            perPage: $perPage,
        );
    }

    public function meta(Tenant $tenant, array $filters = [], int $perPage = 10): array
    {
        return $this->queryCache->meta($tenant, 'sales', $filters, $perPage);
    }

    public function forget(Tenant $tenant): void
    {
        $this->queryCache->forget($tenant, 'sales');
    }
}
