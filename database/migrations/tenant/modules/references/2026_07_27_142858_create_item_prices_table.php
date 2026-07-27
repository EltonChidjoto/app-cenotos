<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('item_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('line');
            $table->decimal('price');
            $table->date('date')->default(now());
            $table->enum('vat_included', ['Nao', 'Sim', 'Definido na Familia'])->nullable();
            $table->string('currency_id')->nullable();
            $table->decimal('discount_1')->default(0);
            $table->decimal('discount_amount')->default(0);
            $table->decimal('before_price')->default(0);
            $table->date('date_before_price')->default(now());
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_prices');
    }
};
