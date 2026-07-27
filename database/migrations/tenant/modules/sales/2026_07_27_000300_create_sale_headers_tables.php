<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_headers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('document_type_id')->constrained('document_types')->restrictOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('seller_id')->nullable()->constrained('sellers')->nullOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->foreignId('term_payment_id')->nullable()->constrained('term_payments')->nullOnDelete();
            $table->foreignId('bank_id')->nullable()->constrained('banks')->nullOnDelete();
            $table->foreignId('vat_exemption_id')->nullable()->constrained('vat_exemptions')->nullOnDelete();
            $table->string('document_number', 60);
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

            $table->unique(['document_number']);
            $table->index(['document_date', 'status']);
            $table->index('created_by_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_headers');
    }
};
