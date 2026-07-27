<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

#[Fillable(['code', 'name', 'description', 'active'])]
class Module extends Model
{
    use CentralConnection;

    protected function casts(): array
    {
        return [
            'active' => 'bool',
        ];
    }

    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'module_resources')
            ->withTimestamps();
    }

    public function tenantInstallations(): HasMany
    {
        return $this->hasMany(TenantModule::class);
    }
}
