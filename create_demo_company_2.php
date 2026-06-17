<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Sale;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tenantId = 'empresa_demo_2';
$email = 'demo2@empresa.com';
$password = '12345678';
$name = 'Demo Usuario 2';
$tenantName = 'Empresa Demo 2';
$tenantSlug = 'empresa_demo_2';

$sales = [
    ['reference' => 'D2-1001', 'customer_name' => 'Cliente Demo 2-1', 'amount' => 4200.00, 'status' => 'paid'],
    ['reference' => 'D2-1002', 'customer_name' => 'Cliente Demo 2-2', 'amount' => 8600.50, 'status' => 'pending'],
    ['reference' => 'D2-1003', 'customer_name' => 'Cliente Demo 2-3', 'amount' => 15250.00, 'status' => 'paid'],
    ['reference' => 'D2-1004', 'customer_name' => 'Cliente Demo 2-4', 'amount' => 990.00, 'status' => 'canceled'],
    ['reference' => 'D2-1005', 'customer_name' => 'Cliente Demo 2-5', 'amount' => 7350.25, 'status' => 'paid'],
];

echo "--- Current tenants and users ---\n";
foreach (Tenant::query()->get() as $row) {
    echo "tenant: id={$row->id} name={$row->name} slug={$row->slug} active={$row->active} data=" . json_encode($row->data) . PHP_EOL;
}
foreach (User::query()->get() as $row) {
    echo "user: id={$row->id} email={$row->email} tenant_id={$row->tenant_id}" . PHP_EOL;
}

$tenantRow = DB::table('tenants')->where('id', $tenantId)->first();

if (! $tenantRow) {
    DB::table('tenants')->insert([
        'id' => $tenantId,
        'name' => $tenantName,
        'slug' => $tenantSlug,
        'active' => true,
        'data' => json_encode(['created_by' => 'copilot', 'demo' => 2]),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    echo "Inserted tenant {$tenantId}.\n";
} else {
    DB::table('tenants')->where('id', $tenantId)->update([
        'name' => $tenantName,
        'slug' => $tenantSlug,
        'active' => true,
        'data' => json_encode(['created_by' => 'copilot', 'demo' => 2]),
        'updated_at' => now(),
    ]);

    echo "Tenant {$tenantId} already exists.\n";
}

$tenant = Tenant::query()->findOrFail($tenantId);

$databaseName = $tenant->database()->getName();
$databaseManager = $tenant->database()->manager();

if (! $databaseManager->databaseExists($databaseName)) {
    $tenant->database()->makeCredentials();
    $databaseManager->createDatabase($tenant);
    echo "Created tenant database {$databaseName}.\n";
} else {
    echo "Tenant database {$databaseName} already exists.\n";
}

Artisan::call('tenants:migrate', [
    '--tenants' => [$tenant->getTenantKey()],
]);

tenancy()->initialize($tenant);

try {
    if (! Schema::hasTable('sales')) {
        Schema::create('sales', function (Blueprint $table): void {
            $table->id();
            $table->string('reference')->unique();
            $table->string('customer_name');
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('paid');
            $table->timestamp('sold_at')->nullable();
            $table->timestamps();
        });
    }

    Sale::query()->delete();

    foreach ($sales as $index => $sale) {
        Sale::query()->create([
            'reference' => $sale['reference'],
            'customer_name' => $sale['customer_name'],
            'amount' => $sale['amount'],
            'status' => $sale['status'],
            'sold_at' => now()->subDays($index),
        ]);
    }

    echo "Seeded 5 sales for {$tenantId}.\n";
} finally {
    tenancy()->end();
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
