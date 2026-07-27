<?php

use App\Models\Tenant;
use App\Services\TenantModuleMigrator;
use App\Services\TenantProvisioner;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('tenant:provision {tenantId : ID do tenant} {--module=* : Módulo(s) a instalar, por exemplo sales} {--seed : Também inserir dados demo}', function (TenantProvisioner $provisioner): int {
    $tenant = Tenant::query()->find($this->argument('tenantId'));

    if ($tenant === null) {
        $this->error("Tenant [{$this->argument('tenantId')}] não encontrado.");

        return self::FAILURE;
    }

    try {
        $provisioner->provision(
            $tenant,
            (bool) $this->option('seed'),
            array_values(array_filter($this->option('module'))),
        );
    } catch (Throwable $exception) {
        report($exception);
        $this->error($exception->getMessage());

        return self::FAILURE;
    }

    $this->info("Tenant [{$tenant->getTenantKey()}] provisionado com sucesso.");

    return self::SUCCESS;
})->purpose('Criar a base e executar migrations Core e dos módulos indicados');

Artisan::command('tenant:module-migrate {tenantId : ID do tenant} {moduleCode : Código do módulo, por exemplo sales}', function (TenantModuleMigrator $migrator): int {
    $tenant = Tenant::query()->find($this->argument('tenantId'));

    if ($tenant === null) {
        $this->error("Tenant [{$this->argument('tenantId')}] não encontrado.");

        return self::FAILURE;
    }

    try {
        $migrator->migrate($tenant, (string) $this->argument('moduleCode'));
    } catch (Throwable $exception) {
        report($exception);
        $this->error($exception->getMessage());

        return self::FAILURE;
    }

    $this->info(
        "Módulo [{$this->argument('moduleCode')}] migrado com sucesso para o tenant [{$tenant->getTenantKey()}]."
    );

    return self::SUCCESS;
})->purpose('Executar as migrations de um módulo numa base tenant específica');
