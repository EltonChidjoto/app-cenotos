<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

#[Fillable(['code', 'name', 'type', 'schema_name', 'table_name'])]
class Resource extends Model
{
    use CentralConnection;

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'module_resources')
            ->withTimestamps();
    }
}
