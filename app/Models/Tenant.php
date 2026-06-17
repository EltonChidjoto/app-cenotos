<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    protected $fillable = [
        'id',
        'name',
        'slug',
        'active',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'active' => 'bool',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function displayName(): string
    {
        return $this->name ?: $this->slug ?: (string) $this->getKey();
    }
}
