<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Module;
use App\Models\Tenant;
use App\Models\TenantModule;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class TenantModuleMigrator
{
    public function migrate(Tenant $tenant, string $moduleCode): void
    {
        $moduleCode = strtolower(trim($moduleCode));
        $definition = config("tenancy.module_migrations.{$moduleCode}");

        if (! is_array($definition)) {
            throw new InvalidArgumentException("O módulo [{$moduleCode}] não possui migrations configuradas.");
        }

        foreach ($definition['dependencies'] ?? [] as $dependencyCode) {
            $this->migrate($tenant, (string) $dependencyCode);
        }

        $module = Module::query()
            ->where('code', $moduleCode)
            ->where('active', true)
            ->first();

        if ($module === null) {
            throw new InvalidArgumentException("O módulo activo [{$moduleCode}] não existe na base central.");
        }

        Cache::store(config('tenancy.lock_store', 'file'))
            ->lock("tenant-module-migrate:{$tenant->getTenantKey()}:{$moduleCode}", 600)
            ->block(30, function () use ($tenant, $module, $moduleCode, $definition): void {
                $this->migrateWithoutLock($tenant, $module, $moduleCode, $definition);
            });
    }

    /**
     * @param  array{version?: string, dependencies?: array<int, string>, paths?: array<int, string>}  $definition
     */
    private function migrateWithoutLock(
        Tenant $tenant,
        Module $module,
        string $moduleCode,
        array $definition,
    ): void {
        $version = (string) ($definition['version'] ?? '1.0.0');
        $modulePaths = $definition['paths'] ?? [];
        $corePaths = config('tenancy.core_migration_paths', []);
        $paths = array_values(array_unique([...$corePaths, ...$modulePaths]));

        if ($modulePaths === [] || $paths === []) {
            throw new RuntimeException("Não existem paths de migration para o módulo [{$moduleCode}].");
        }

        $installation = TenantModule::query()->updateOrCreate(
            [
                'tenant_id' => $tenant->getTenantKey(),
                'module_id' => $module->getKey(),
            ],
            [
                'status' => 'provisioning',
                'last_error' => null,
            ],
        );

        try {
            $this->runMigrations($tenant, $paths);

            $tenant->run(function () use ($moduleCode, $version): void {
                $now = now();

                DB::table('installed_modules')->updateOrInsert(
                    ['code' => $moduleCode],
                    [
                        'version' => $version,
                        'status' => 'active',
                        'installed_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                );
            });

            $installation->forceFill([
                'status' => 'active',
                'installed_version' => $version,
                'activated_at' => $installation->activated_at ?? now(),
                'migrated_at' => now(),
                'last_error' => null,
            ])->save();
        } catch (Throwable $exception) {
            if (function_exists('tenancy') && tenancy()->initialized) {
                tenancy()->end();
            }

            $installation->forceFill([
                'status' => 'failed',
                'last_error' => mb_substr($exception->getMessage(), 0, 4000),
            ])->save();

            throw $exception;
        }
    }

    /**
     * @param  array<int, string>  $paths
     */
    private function runMigrations(Tenant $tenant, array $paths): void
    {
        $exitCode = Artisan::call('tenants:migrate', [
            '--tenants' => [$tenant->getTenantKey()],
            '--path' => $paths,
            '--realpath' => true,
            '--force' => true,
        ]);

        if ($exitCode !== 0) {
            throw new RuntimeException(
                "As migrations falharam para o tenant [{$tenant->getTenantKey()}].\n".Artisan::output()
            );
        }
    }
}
