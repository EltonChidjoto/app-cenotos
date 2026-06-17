<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'reference',
        'customer_name',
        'amount',
        'status',
        'sold_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'sold_at' => 'datetime',
        ];
    }
}
