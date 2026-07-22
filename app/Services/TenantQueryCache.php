<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;
use Closure;

class TenantQueryCache
{
    /**
     * Cache a paginated query result per tenant.
     *
     * @param  Closure(Builder): Builder  $queryFactory
     */
    public function paginate(
        Tenant $tenant,
        string $domain,
        string $modelClass,
        Closure $queryFactory,
        array $filters = [],
        int $perPage = 10,
    ): LengthAwarePaginator {
        $page = (int) ($filters['page'] ?? Paginator::resolveCurrentPage() ?: 1);
        $normalizedFilters = $this->normalizeFilters($filters);
        unset($normalizedFilters['page']);

        if (config('cache.default') !== 'redis') {
            return $this->paginateWithoutCache($queryFactory, $filters, $perPage, $page);
        }

        try {
            $cache = $this->cacheStore();
            $scopedCache = $this->supportsTags($cache) ? $cache->tags($this->cacheTags($tenant, $domain)) : $cache;
            $cacheKey = $this->cacheKey($tenant, $domain, $page, $perPage, $normalizedFilters);

            $payload = $scopedCache->remember($cacheKey, now()->addMinutes(10), function () use ($queryFactory, $page, $perPage, $filters): array {
                $paginator = $queryFactory()
                    ->paginate($perPage, ['*'], 'page', $page)
                    ->appends($filters);

                return [
                    'items' => $paginator->getCollection()->toArray(),
                    'total' => $paginator->total(),
                    'per_page' => $paginator->perPage(),
                    'current_page' => $paginator->currentPage(),
                    'path' => $paginator->path(),
                ];
            });

            return new Paginator(
                $modelClass::hydrate($payload['items']),
                $payload['total'],
                $payload['per_page'],
                $payload['current_page'],
                [
                    'path' => $payload['path'],
                    'pageName' => 'page',
                    'query' => $filters,
                ]
            );
        } catch (\Throwable) {
            // Redis is optional: if it is unavailable, query the tenant database directly.
            return $this->paginateWithoutCache($queryFactory, $filters, $perPage, $page);
        }
    }

    public function meta(Tenant $tenant, string $domain, array $filters = [], int $perPage = 10): array
    {
        $page = (int) ($filters['page'] ?? Paginator::resolveCurrentPage() ?: 1);
        $normalizedFilters = $this->normalizeFilters($filters);
        unset($normalizedFilters['page']);

        return [
            'store' => config('cache.default'),
            'key' => $this->cacheKey($tenant, $domain, $page, $perPage, $normalizedFilters),
            'tags' => $this->cacheTags($tenant, $domain),
            'page' => $page,
            'per_page' => $perPage,
            'domain' => $domain,
        ];
    }

    public function forget(Tenant $tenant, string $domain): void
    {
        if (config('cache.default') !== 'redis') {
            return;
        }

        try {
            $cache = $this->cacheStore();

            if ($this->supportsTags($cache)) {
                $cache->tags($this->cacheTags($tenant, $domain))->flush();
            }
        } catch (\Throwable) {
            // Cache invalidation must not prevent the model operation from completing.
        }
    }

    private function paginateWithoutCache(
        Closure $queryFactory,
        array $filters,
        int $perPage,
        int $page,
    ): LengthAwarePaginator {
        return $queryFactory()
            ->paginate($perPage, ['*'], 'page', $page)
            ->appends($filters);
    }

    private function cacheStore(): CacheRepository
    {
        return Cache::store(config('cache.default'));
    }

    private function cacheKey(Tenant $tenant, string $domain, int $page, int $perPage, array $filters): string
    {
        return sprintf(
            '%s:%s:page:%d:per:%d:filters:%s',
            $tenant->getTenantKey(),
            $domain,
            $page,
            $perPage,
            md5(json_encode($filters, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE))
        );
    }

    private function cacheTags(Tenant $tenant, string $domain): array
    {
        return [
            $tenant->getTenantKey(),
            $domain,
        ];
    }

    private function normalizeFilters(array $filters): array
    {
        ksort($filters);

        return Arr::where($filters, static fn ($value) => $value !== null && $value !== '');
    }

    private function supportsTags(CacheRepository $cache): bool
    {
        return method_exists($cache, 'tags');
    }
}
