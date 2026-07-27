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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->string('description', 255)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        DB::table('modules')->insert([
            [
                'code' => 'sales',
                'name' => 'Vendas',
                'description' => 'Gestão de clientes, artigos, vendas, pagamentos e documentos comerciais.',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'references',
                'name' => 'Referências',
                'description' => 'Gestão de entidade, atalhos',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
