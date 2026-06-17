<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table): void {
            $table->string('name')->after('id');
            $table->string('slug')->unique()->after('name');
            $table->boolean('active')->default(true)->after('slug');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->string('tenant_id')->nullable()->after('password');
            $table->index('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['tenant_id']);
            $table->dropIndex(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table('tenants', function (Blueprint $table): void {
            $table->dropColumn(['name', 'slug', 'active']);
        });
    }
};
