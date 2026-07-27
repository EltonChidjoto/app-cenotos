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
        Schema::create('payment_method_document_copies', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->constrained('payment_methods')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('document_copy_id')->constrained('document_copies')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
        });

        DB::table('payment_method_document_copies')->insert([
            ['payment_method_id' => 1, 'document_copy_id' => 1],
            ['payment_method_id' => 2, 'document_copy_id' => 1],
            ['payment_method_id' => 3, 'document_copy_id' => 1],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_method_document_copies');
    }
};
