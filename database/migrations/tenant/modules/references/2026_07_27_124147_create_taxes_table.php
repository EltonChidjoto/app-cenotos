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
        Schema::create('taxes', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 150);
            $table->decimal('percentage', 8, 4)->default(0);
            $table->foreignId('vat_exemption_id')->nullable()->constrained('vat_exemptions')->cascadeOnUpdate()->nullOnDelete();
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
        });

        DB::table('taxes')->insert([
            [
                'name' => 'Isento de IVA',
                'percentage' => 0,
                'vat_exemption_id' => null,
                'active' => true,
                'user_id' => null,
            ],
            [
                'name' => 'Taxa normal 16%',
                'percentage' => 16,
                'vat_exemption_id' => null,
                'active' => true,
                'user_id' => null,
            ],
            [
                'name' => 'Taxa normal 50% dedutivel',
                'percentage' => 50,
                'vat_exemption_id' => null,
                'active' => true,
                'user_id' => null,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
