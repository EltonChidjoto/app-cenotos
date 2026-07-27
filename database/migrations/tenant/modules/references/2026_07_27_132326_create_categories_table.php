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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
        });

        DB::table('categories')->insert([
            [
                'name' => 'Mercadoria',
                'active' => true,
                'user_id' => null
            ],
            [
                'name' => 'Matérias primas',
                'active' => true,
                'user_id' => null
            ],
            [
                'name' => 'Produto acabados ou intermédios',
                'active' => true,
                'user_id' => null
            ],
            [
                'name' => 'Serviços',
                'active' => true,
                'user_id' => null
            ],
            [
                'name' => 'Imobilizado',
                'active' => true,
                'user_id' => null
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
