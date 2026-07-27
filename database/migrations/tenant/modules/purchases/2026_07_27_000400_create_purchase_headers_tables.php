<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_headers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('document_type_id')->constrained('document_types')->restrictOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->foreignId('term_payment_id')->nullable()->constrained('term_payments')->nullOnDelete();
            $table->foreignId('bank_id')->nullable()->constrained('banks')->nullOnDelete();
            $table->foreignId('vat_exemption_id')->nullable()->constrained('vat_exemptions')->nullOnDelete();
            $table->string('document_number', 60);
            $table->string('supplier_document_number', 60)->nullable();
            $table->dateTime('document_date');
            $table->date('due_date')->nullable();
            $table->string('status', 30)->default('draft');
            $table->char('currency', 3)->default('MZN');
            $table->decimal('exchange_rate', 18, 6)->default(1);
            $table->decimal('subtotal', 18, 4)->default(0);
            $table->decimal('discount_total', 18, 4)->default(0);
            $table->decimal('tax_total', 18, 4)->default(0);
            $table->decimal('grand_total', 18, 4)->default(0);
            $table->unsignedBigInteger('created_by_user_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['document_type_id', 'document_number']);
            $table->index(['document_date', 'status']);
            $table->index('created_by_user_id');
        });

        Schema::create('purchase_lines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('purchase_header_id')->constrained('purchase_headers')->cascadeOnDelete();
            $table->foreignId('article_id')->nullable()->constrained('articles')->nullOnDelete();
            $table->foreignId('tax_id')->nullable()->constrained('taxes')->nullOnDelete();
            $table->unsignedInteger('line_number');
            $table->string('description', 255);
            $table->decimal('quantity', 18, 4);
            $table->decimal('unit_cost', 18, 4);
            $table->decimal('discount_percentage', 8, 4)->default(0);
            $table->decimal('discount_amount', 18, 4)->default(0);
            $table->decimal('tax_percentage', 8, 4)->default(0);
            $table->decimal('tax_amount', 18, 4)->default(0);
            $table->decimal('line_total', 18, 4);
            $table->timestamps();

            $table->unique(['purchase_header_id', 'line_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_lines');
        Schema::dropIfExists('purchase_headers');
    }
};
