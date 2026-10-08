<?php

declare(strict_types=1);

namespace App\Models\Modules\Sales;

use App\Services\TenantService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

#[Fillable(['document_type_id', 'customer_id', 'seller_id', 'payment_method_id', 'term_payment_id', 'bank_id', 'vat_exemption_id', 'document_number', 'document_date', 'due_date', 'status', 'currency', 'exchange_rate', 'subtotal', 'discount_total', 'tax_total', 'grand_total', 'created_by_user_id', 'notes', 'user_created_id', 'user_updated_id' ])]
class SaleHeader extends Model
{
    use TenantConnection;

    protected function casts(): array {
        return [
            'amount' => 'decimal:2',
            'sold_at' => 'datetime',
        ];
    }

    protected static function booted(): void {
        $flushTenantCache = function (): void {
            if (! function_exists('tenancy') || ! tenancy()->initialized) {
                return;
            }

            app(TenantService::class)->forgetCache('sales', tenancy()->tenant);
        };

        static::saved($flushTenantCache);
        static::deleted($flushTenantCache);
    }
}
