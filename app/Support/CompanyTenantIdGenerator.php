<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Str;
use Stancl\Tenancy\Contracts\UniqueIdentifierGenerator;

class CompanyTenantIdGenerator implements UniqueIdentifierGenerator
{
    public static function generate($resource): string
    {
        $candidates = [
            data_get($resource, 'slug'),
            data_get($resource, 'name'),
            data_get($resource, 'data.slug'),
            data_get($resource, 'data.name'),
        ];

        foreach ($candidates as $candidate) {
            if (! is_string($candidate) || $candidate === '') {
                continue;
            }

            $identifier = Str::of($candidate)
                ->ascii()
                ->lower()
                ->replaceMatches('/[^a-z0-9]+/', '_')
                ->trim('_')
                ->toString();

            if ($identifier !== '') {
                return $identifier;
            }
        }

        return 'empresa_' . Str::lower(Str::random(8));
    }
}
