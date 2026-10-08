<?php

namespace App\Models\Modules\Sales;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

#[Fillable(['sale_header_id', 'article_id', 'tax_id', 'line_number', 'description', 'quantity', 'unit_price', 'discount_percentage', 'discount_amount', 'tax_percentage', 'tax_amount', 'line_total', 'user_created_id', 'user_updated_id'])]
class SaleLine extends Model
{
    use TenantConnection;
}
