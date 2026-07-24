<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use RuntimeException;

class TenantProvisioner
{
    public function provision(Tenant $tenant, bool $seed = false): void
    {
        $manager = $tenant->database()->manager();
        $databaseName = $tenant->database()->getName();

        if (! $manager->databaseExists($databaseName)) {
            $tenant->database()->makeCredentials();

            if (! $manager->createDatabase($tenant) && ! $manager->databaseExists($databaseName)) {
                throw new RuntimeException("Não foi possível criar a base do tenant [{$tenant->getTenantKey()}].");
            }
        }

        $this->runTenantCommand('tenants:migrate', $tenant);

        if ($seed) {
            $this->runTenantCommand('tenants:seed', $tenant);
        }
    }

    private function runTenantCommand(string $command, Tenant $tenant): void
    {
        $exitCode = Artisan::call($command, [
            '--tenants' => [$tenant->getTenantKey()],
            '--force' => true,
        ]);

        if ($exitCode !== 0) {
            throw new RuntimeException(
                "O comando {$command} falhou para o tenant [{$tenant->getTenantKey()}].\n" . Artisan::output()
            );
        }
    }
}
