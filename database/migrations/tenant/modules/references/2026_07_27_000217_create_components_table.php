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
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
        });

        DB::table('components')->insert([
            ['name' => 'Valor / Cheque'],
            ['name' => 'Numerário'],
            ['name' => 'Talão de Déposito'],
            ['name' => 'Transferência Bancária'],
            ['name' => 'Cartão de Crédito'],
            ['name' => 'Cartão de Débito'],
            ['name' => 'Adiantamento'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('components');
    }
};
