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
        Schema::create('contacts', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 150);
            $table->string('position', 150)->nullable();
            $table->foreignId('entity_type_id')->default(1)->constrained('entity_types')->cascadeOnUpdate();
            $table->integer('register')->nullable();
            $table->integer('direction')->nullable();
            $table->integer('nuit')->nullable();
            $table->string('address_1', 150)->nullable();
            $table->string('address_2', 150)->nullable();
            $table->string('locality')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('province_id')->nullable()->constrained('provinces')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->cascadeOnUpdate()->nullOnDelete();
            $table->string('zip_code', 20)->nullable();
            $table->string('company_phone', 30)->nullable();
            $table->string('private_phone', 30)->nullable();
            $table->string('mobile_phone', 30)->nullable();
            $table->string('fax', 30)->nullable();
            $table->string('email_1', 200)->nullable();
            $table->string('email_2', 200)->nullable();
            $table->string('website', 200)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
