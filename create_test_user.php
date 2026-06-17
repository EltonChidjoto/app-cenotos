<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$tenantId = 'empresa_demo';
$email = 'demo@empresa.com';
$password = '12345678';
$name = 'Demo Usuario';
$tenantName = 'Empresa Demo';
$tenantSlug = 'empresa_demo';

$tenant = Tenant::query()->firstOrCreate(
    ['id' => $tenantId],
    [
        'name' => $tenantName,
        'slug' => $tenantSlug,
        'active' => true,
    ]
);

$user = User::query()->firstOrCreate(
    ['email' => $email],
    [
        'name' => $name,
        'password' => Hash::make($password),
        'tenant_id' => $tenant->getKey(),
    ]
);

if ($user->wasRecentlyCreated) {
    echo "Created tenant and user.\n";
} else {
    echo "User already exists.\n";
    if ($user->tenant_id !== $tenant->getKey()) {
        $user->tenant_id = $tenant->getKey();
        $user->save();
        echo "Assigned existing user to tenant.\n";
    }
}

echo "Tenant ID: {$tenant->getKey()}\n";
echo "Tenant name: {$tenant->name}\n";
echo "User email: {$user->email}\n";
echo "User password: {$password}\n";

echo "Login credentials ready for http://127.0.0.1:8000/login\n";
