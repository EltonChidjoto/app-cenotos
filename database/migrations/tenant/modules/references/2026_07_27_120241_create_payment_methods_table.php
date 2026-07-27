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
        Schema::create('payment_methods', function (Blueprint $table): void {
            $table->id();
            $table->string('abbreviation', 15)->unique();
            $table->string('name', 150);
            $table->foreignId('component_id')->default(1)->constrained('components')->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('accept_change')->default(false);
            $table->boolean('open_drawer')->default(false);
            $table->string('document', 255)->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
        });

        DB::table('payment_methods')->insert([
            ['abbreviation' => 'CHECK', 'name' => 'Cheque', 'component_id' => 1, 'accept_change' => false, 'open_drawer' => false, 'document' => null, 'active' => true],
            ['abbreviation' => 'TLDP', 'name' => 'Talão de Déposito', 'component_id' => 3, 'accept_change' => true, 'open_drawer' => true, 'document' => null, 'active' => true],
            ['abbreviation' => 'TRNS', 'name' => 'Transferência', 'component_id' => 4, 'accept_change' => false, 'open_drawer' => false, 'document' => null, 'active' => true],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
