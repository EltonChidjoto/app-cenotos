<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table): void {
            if (! Schema::hasColumn('tenants', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('id');
            }

            if (! Schema::hasColumn('tenants', 'name')) {
                $table->string('name')->nullable()->after('slug');
            }

            if (! Schema::hasColumn('tenants', 'active')) {
                $table->boolean('active')->default(true)->after('name');
            }
        });

        $usedSlugs = [];

        DB::table('tenants')
            ->select(['id', 'slug', 'name'])
            ->orderBy('id')
            ->get()
            ->each(function (object $tenant) use (&$usedSlugs): void {
                $slug = $tenant->slug ?: Str::of((string) $tenant->id)
                    ->ascii()
                    ->lower()
                    ->replaceMatches('/[^a-z0-9]+/', '_')
                    ->trim('_')
                    ->toString();

                $slug = $slug !== '' ? $slug : 'tenant';

                if (isset($usedSlugs[$slug])) {
                    $slug = Str::substr($slug, 0, 48).'_'.Str::substr(sha1((string) $tenant->id), 0, 8);
                }

                $usedSlugs[$slug] = true;

                DB::table('tenants')
                    ->where('id', $tenant->id)
                    ->update([
                        'slug' => $slug,
                        'name' => $tenant->name ?: 'Tenant '.$tenant->id,
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table): void {
            foreach (['active', 'name', 'slug'] as $column) {
                if (Schema::hasColumn('tenants', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
