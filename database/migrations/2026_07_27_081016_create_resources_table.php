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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->string('name', 150);
            $table->string('type', 30)->nullable();
            $table->string('schema_name', 100)->nullable();
            $table->string('table_name', 100)->nullable();
            $table->timestamps();
        });

        DB::table('resources')->insert([
            [
                'code' => 'article',
                'name' => 'Article',
                'type' => 'table',
                'schema_name' => null,
                'table_name' => 'articles',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'bank',
                'name' => 'Bank',
                'type' => 'table',
                'schema_name' => null,
                'table_name' => 'banks',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'contact',
                'name' => 'Contact',
                'type' => 'table',
                'schema_name' => null,
                'table_name' => 'contacts',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'customer',
                'name' => 'Customer',
                'type' => 'table',
                'schema_name' => null,
                'table_name' => 'customers',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'entity_category',
                'name' => 'Entity Category',
                'type' => 'table',
                'schema_name' => null,
                'table_name' => 'entity_categories',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'payment_method',
                'name' => 'Payment Method',
                'type' => 'table',
                'schema_name' => null,
                'table_name' => 'payment_methods',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'purchase_header',
                'name' => 'Purchase Header',
                'type' => 'table',
                'schema_name' => null,
                'table_name' => 'purchase_headers',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'purchase_line',
                'name' => 'Purchase Line',
                'type' => 'table',
                'schema_name' => null,
                'table_name' => 'purchase_lines',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'sale_header',
                'name' => 'Sale Header',
                'type' => 'table',
                'schema_name' => null,
                'table_name' => 'sale_headers',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'sale_line',
                'name' => 'Sale Line',
                'type' => 'table',
                'schema_name' => null,
                'table_name' => 'sale_lines',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'seller',
                'name' => 'Seller',
                'type' => 'table',
                'schema_name' => null,
                'table_name' => 'sellers',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'supplier',
                'name' => 'Supplier',
                'type' => 'table',
                'schema_name' => null,
                'table_name' => 'suppliers',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'document_type',
                'name' => 'Document Type',
                'type' => 'table',
                'schema_name' => null,
                'table_name' => 'document_types',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'tax',
                'name' => 'Tax',
                'type' => 'table',
                'schema_name' => null,
                'table_name' => 'taxes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'term_payment',
                'name' => 'Terms of payment',
                'type' => 'table',
                'schema_name' => null,
                'table_name' => 'term_payments',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'vat_exemption',
                'name' => 'VAT Exemption',
                'type' => 'table',
                'schema_name' => null,
                'table_name' => 'vat_exemptions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
