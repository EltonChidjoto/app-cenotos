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
        Schema::create('term_payments', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 150);
            $table->unsignedInteger('days')->default(0);
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
        });

        DB::table('term_payments')->insert([
            ['name' => 'A Pronto', 'days' => 0, 'active' => true],
            ['name' => 'A 15 dias', 'days' => 15, 'active' => true],
            ['name' => 'A 30 dias', 'days' => 30, 'active' => true],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('term_payments');
    }
};
