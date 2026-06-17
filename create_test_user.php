<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

$tenantId = 'empresa_demo';
$email = 'demo@empresa.com';
$password = '12345678';
$name = 'Demo Usuario';
$tenantName = 'Empresa Demo';
$tenantSlug = 'empresa_demo';

echo "--- Current tenants and users ---\n";
foreach (Tenant::query()->get() as $row) {
    echo "tenant: id={$row->id} name={$row->name} slug={$row->slug} active={$row->active} data=" . json_encode($row->data) . PHP_EOL;
}
foreach (User::query()->get() as $row) {
    echo "user: id={$row->id} email={$row->email} tenant_id={$row->tenant_id}" . PHP_EOL;
}

$tenant = Tenant::query()->firstOrCreate(
    ['id' => $tenantId],
    [
        'name' => $tenantName,
        'slug' => $tenantSlug,
        'active' => true,
        'data' => ['created_by' => 'copilot'],
    ]
);

echo $tenant->wasRecentlyCreated
    ? "Inserted tenant {$tenantId}.\n"
    : "Tenant {$tenantId} already exists.\n";

$databaseName = $tenant->database()->getName();
$databaseManager = $tenant->database()->manager();

if (! $databaseManager->databaseExists($databaseName)) {
    $tenant->database()->makeCredentials();
    $databaseManager->createDatabase($tenant);

    Artisan::call('tenants:migrate', [
        '--tenants' => [$tenant->getTenantKey()],
    ]);

    Artisan::call('tenants:seed', [
        '--tenants' => [$tenant->getTenantKey()],
    ]);

    echo "Provisioned tenant database {$databaseName}.\n";
} else {
    echo "Tenant database {$databaseName} already exists.\n";
}

$user = User::query()->updateOrCreate(
    ['email' => $email],
    [
        'name' => $name,
        'password' => $password,
        'tenant_id' => $tenantId,
    ]
);

echo $user->wasRecentlyCreated
    ? "Inserted user {$email}.\n"
    : "Updated existing user {$email}.\n";

echo "\nCredentials:\n";
echo "Email: {$email}\n";
echo "Password: {$password}\n";
echo "Tenant slug: {$tenantSlug}\n";
echo "Login URL: http://127.0.0.1:8000/login\n";
