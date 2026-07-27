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
        Schema::create('document_copies', function (Blueprint $table) {
            $table->id();
            $table->string('description', 150)->unique();
            $table->boolean('defined')->default(true);
            $table->string('print', 100)->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
        });

        DB::table('document_copies')->insert([
            ['description' => 'Original', 'defined' => true, 'print' => 'Microsoft Print to PDF', 'user_id' => null],
            ['description' => 'Duplicado', 'defined' => false, 'print' => null, 'user_id' => null],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_copies');
    }
};
