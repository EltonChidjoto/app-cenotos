<?php

use App\Models\Tenant;
use App\Services\TenantProvisioner;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Throwable;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('tenant:provision {tenantId : ID do tenant} {--seed : Também inserir dados demo}', function (
    TenantProvisioner $provisioner,
): int {
    $tenant = Tenant::query()->find($this->argument('tenantId'));

    if ($tenant === null) {
        $this->error("Tenant [{$this->argument('tenantId')}] não encontrado.");

        return self::FAILURE;
    }

    try {
        $provisioner->provision($tenant, (bool) $this->option('seed'));
    } catch (Throwable $exception) {
        report($exception);
        $this->error($exception->getMessage());

        return self::FAILURE;
    }

    $this->info("Tenant [{$tenant->getTenantKey()}] provisionado com sucesso.");

    return self::SUCCESS;
})->purpose('Criar a base e executar migrations de um tenant');
