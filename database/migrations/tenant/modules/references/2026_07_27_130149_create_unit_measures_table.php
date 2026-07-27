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
        Schema::create('unit_measures', function (Blueprint $table) {
            $table->id();
            $table->string('abbreviation', 15)->unique();
            $table->string('name', 100);
            $table->unsignedTinyInteger('decimal_places')->default(3);
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
        });

        DB::table('unit_measures')->insert([
            [
                'abbreviation' => 'Cx6',
                'name' => 'Caixa 6 unidades',
                'decimal_places' => 3,
                'active' => true
            ],
            [
                'abbreviation' => 'Cx12',
                'name' => 'Caixa 12 unidades',
                'decimal_places' => 3,
                'active' => true
            ],
            [
                'abbreviation' => 'Kg',
                'name' => 'Kilograma',
                'decimal_places' => 3,
                'active' => true
            ],
            [
                'abbreviation' => 'Lt',
                'name' => 'Litro',
                'decimal_places' => 3,
                'active' => true
            ],
            [
                'abbreviation' => 'Mt',
                'name' => 'Metro Linear',
                'decimal_places' => 3,
                'active' => true
            ],
            [
                'abbreviation' => 'Mt2',
                'name' => 'Metro quadrado',
                'decimal_places' => 3,
                'active' => true
            ],
            [
                'abbreviation' => 'Mt3',
                'name' => 'Metro cúbico',
                'decimal_places' => 3,
                'active' => true
            ],
            [
                'abbreviation' => 'Un',
                'name' => 'Unidade',
                'decimal_places' => 3,
                'active' => true
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_measures');
    }
};
