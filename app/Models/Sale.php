<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\TenantSalesCache;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class Sale extends Model
{
    use TenantConnection;

    protected $fillable = [
        'reference',
        'customer_name',
        'amount',
        'status',
        'sold_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'sold_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        $flushTenantCache = function (): void {
            if (! function_exists('tenancy') || ! tenancy()->initialized) {
                return;
            }

            app(TenantSalesCache::class)->forget(tenancy()->tenant);
        };

        static::saved($flushTenantCache);
        static::deleted($flushTenantCache);
    }
}
