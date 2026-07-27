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
        Schema::create('entity_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 150);
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
        });

        DB::table('entity_categories')->insert([
            ['name' => 'Entidade gerais', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Entidade - Empresa mãe', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Entidade - Empresa Subsidiárias', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Entidade - Empresa Associadas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Entidade - Empreendimentos Conjuntos', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Entidade - Outras Partes Relacionadas', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_categories');
    }
};
