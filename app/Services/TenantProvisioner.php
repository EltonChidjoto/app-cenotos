<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

class TenantProvisioner
{
    public function __construct(private readonly TenantModuleMigrator $moduleMigrator) {}

    /**
     * @param  array<int, string>  $modules
     */
    public function provision(Tenant $tenant, bool $seed = false, array $modules = []): void
    {
        Cache::store(config('tenancy.lock_store', 'file'))
            ->lock('tenant-provision:'.$tenant->getTenantKey(), 300)
            ->block(30, function () use ($tenant, $seed, $modules): void {
                $this->provisionWithoutLock($tenant, $seed, $modules);
            });
    }

    /**
     * @param  array<int, string>  $modules
     */
    private function provisionWithoutLock(Tenant $tenant, bool $seed, array $modules): void
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

        foreach (array_unique($modules) as $moduleCode) {
            $this->moduleMigrator->migrate($tenant, $moduleCode);
        }

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
                "O comando {$command} falhou para o tenant [{$tenant->getTenantKey()}].\n".Artisan::output()
            );
        }
    }
}
