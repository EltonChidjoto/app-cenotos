<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('module_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->foreignId('resource_id')->constrained('resources')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['module_id', 'resource_id']);
        });

        $resourcesByModule = [
            'sales' => ['sale_header', 'sale_line'],
            'references' => [
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
            ],
        ];

        foreach ($resourcesByModule as $moduleCode => $resourceCodes) {
            $moduleId = DB::table('modules')->where('code', $moduleCode)->value('id');

            if ($moduleId === null) {
                continue;
            }

            $now = now();

            DB::table('resources')
                ->whereIn('code', $resourceCodes)
                ->pluck('id')
                ->each(function (int $resourceId) use ($moduleId, $now): void {
                    DB::table('module_resources')->insert([
                        'module_id' => $moduleId,
                        'resource_id' => $resourceId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_resources');
    }
};
