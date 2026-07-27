<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * @var array<int, string>
     */
    private array $resourceCodes = [
        'article',
        'bank',
        'contact',
        'customer',
        'entity_category',
        'payment_method',
        'seller',
        'supplier',
        'document_type',
        'tax',
        'term_payment',
        'vat_exemption',
    ];

    public function up(): void
    {
        $moduleId = DB::table('modules')->where('code', 'references')->value('id');

        if ($moduleId === null) {
            return;
        }

        $now = now();

        DB::table('resources')
            ->whereIn('code', $this->resourceCodes)
            ->pluck('id')
            ->each(function (int $resourceId) use ($moduleId, $now): void {
                DB::table('module_resources')->insertOrIgnore([
                    'module_id' => $moduleId,
                    'resource_id' => $resourceId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            });
    }

    public function down(): void
    {
        $moduleId = DB::table('modules')->where('code', 'references')->value('id');

        if ($moduleId !== null) {
            DB::table('module_resources')->where('module_id', $moduleId)->delete();
        }
    }
};
