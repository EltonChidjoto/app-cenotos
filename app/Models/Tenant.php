<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

#[Fillable(['slug', 'name', 'data', 'active'])]
class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase;

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'active' => 'bool',
        ];
    }

    /**
     * Estes atributos são colunas reais na tabela central de inquilinos.
     * Caso contrário, o pacote armazena atributos desconhecidos dentro do JSON de dados.
     *
     * @return array<int, string>
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'slug',
            'name',
            'data',
            'active',
            'created_at',
            'updated_at',
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
