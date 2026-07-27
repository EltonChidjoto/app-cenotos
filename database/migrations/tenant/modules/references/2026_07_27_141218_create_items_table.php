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
        Schema::create('items', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('barcode')->nullable()->unique();
            $table->string('abbreviation', 15);
            $table->foreignId('category_id')->constrained('categories')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('item_type_id')->constrained('item_types')->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('description')->nullable();

            // Preços
            $table->foreignId('sale_id')->constrained('taxes')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('purchase_id')->constrained('purchases')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('vat_exemption_id')->constrained('vat_exemptions')->cascadeOnUpdate()->cascadeOnDelete();

            // Outros dados I
            $table->foreignId('weight_origin')->nullable()->constrained('countries')->cascadeOnUpdate()->nullOnDelete();
            $table->boolean('dont_move_stock')->default(false);
            $table->boolean('2nd_hand_item')->default(false);
            $table->boolean('does_not_affect_infrastat')->default(false);
            $table->boolean('moves_just_one_unit')->default(false);
            $table->boolean('fuel')->default(false);

            // Outros dados II
            $table->foreignId('stock_id')->constrained('unit_measures')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('sale_id')->constrained('unit_measures')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('purchase_id')->constrained('unit_measures')->cascadeOnUpdate()->cascadeOnDelete();

            // Stock
            $table->decimal('stock_min')->default(0);
            $table->decimal('stock_max')->default(0);
            $table->decimal('stock_rep')->default(0);
            $table->decimal('stock_res')->default(0);
            $table->decimal('stock_real')->default(0);
            $table->decimal('stock_available')->default(0);
            $table->decimal('stock_total_entries')->default(0);
            $table->date('stock_date_entries')->nullable();
            $table->decimal('stock_total_exits')->default(0);
            $table->date('stock_date_exits')->nullable();

            // Oficinas
            $table->boolean('available_auto_repair')->default(true);

            // Observacao
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
        Schema::dropIfExists('items');
    }
};
