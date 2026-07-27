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
        Schema::create('entity_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name')->unique();
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
        });

        DB::table('entity_types')->insert([
            ['slug' => 'contacto', 'name' => 'Contacto', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'cliente', 'name' => 'Cliente', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'fornecedor', 'name' => 'Fornecedor', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'funcionario', 'name' => 'Funcionário', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'vendedor', 'name' => 'Vendedor', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'armazem', 'name' => 'Armazém', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_types');
    }
};
