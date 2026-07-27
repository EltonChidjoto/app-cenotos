<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_tenants', function (Blueprint $table): void {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('tenant_id');
            $table->boolean('is_default')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->primary(['user_id', 'tenant_id']);
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });

        DB::table('users')
            ->whereNotNull('tenant_id')
            ->select(['id', 'tenant_id'])
            ->orderBy('id')
            ->get()
            ->each(function (object $user): void {
                DB::table('user_tenants')->insertOrIgnore([
                    'user_id' => $user->id,
                    'tenant_id' => $user->tenant_id,
                    'is_default' => true,
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_tenants');
    }
};
