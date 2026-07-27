<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

#[Fillable([
    'tenant_id',
    'module_id',
    'status',
    'installed_version',
    'activated_at',
    'migrated_at',
    'last_error',
])]
class TenantModule extends Model
{
    use CentralConnection;

    protected function casts(): array
    {
        return [
            'activated_at' => 'datetime',
            'migrated_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
