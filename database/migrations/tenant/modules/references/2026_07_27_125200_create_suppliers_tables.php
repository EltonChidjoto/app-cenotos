<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 200);
            $table->string('nuit', 30)->unique();
            $table->foreignId('entity_category_id')->nullable()->constrained('entity_categories')->cascadeOnUpdate()->nullOnDelete();
            $table->date('opening_date')->default(now()->toDateString());
            $table->string('address_1', 150)->nullable();
            $table->string('address_2', 150)->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('province_id')->nullable()->constrained('provinces')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->cascadeOnUpdate()->nullOnDelete();
            $table->string('zip_code', 20)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('fax', 30)->nullable();
            $table->string('email', 200)->nullable();
            $table->foreignId('identification_document_type_id')->nullable()->constrained('identification_document_types')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('identification_document_number', 50)->nullable();
            $table->date('date_birth')->nullable();
            $table->foreignId('nationality')->nullable()->constrained('countries')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('place_birth')->nullable()->constrained('countries')->cascadeOnUpdate()->nullOnDelete();
            $table->string('identification_document_issued_in', 100)->nullable();
            $table->date('identification_document_issue_date')->nullable();
            $table->date('identification_document_expiration_date')->nullable();
            $table->enum('gender', ['Indefinido', 'Masculino', 'Femenino'])->default('Indefinido');
            $table->foreignId('bank_id')->nullable()->constrained('banks')->cascadeOnUpdate()->nullOnDelete();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('bank_nib')->nullable();
            $table->string('bank_iban', 50)->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
