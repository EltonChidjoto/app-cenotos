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
        Schema::create('vat_exemptions', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('cod_oficial', 50);
            $table->string('reason', 255);
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
        });

        DB::table('vat_exemptions')->insert([
            [
                'code' => 'M01',
                'cod_oficial' => 'M01',
                'reason' => 'Artigo 16.º n.º 6 alínea c) do CIVA',
                'user_id' => null,
            ],
            [
                'code' => 'M02',
                'cod_oficial' => 'M02',
                'reason' => 'Artigo 6.º do Decreto-Lei n.º 198/90, de 19 de junho',
                'user_id' => null,
            ],
            [
                'code' => 'M03',
                'cod_oficial' => 'M03',
                'reason' => 'Exigibilidade de caixa',
                'user_id' => null,
            ],
            [
                'code' => 'M04',
                'cod_oficial' => 'M04',
                'reason' => 'Exigibilidade de caixa',
                'user_id' => null,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('vat_exemptions');
    }
};
