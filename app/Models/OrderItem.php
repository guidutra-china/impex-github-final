<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price', // Price per unit (integer, e.g., cents)
        'total_price', // quantity * price (integer, e.g., cents)
    ];

    protected $casts = [
        'price' => 'integer',
        'total_price' => 'integer',
        'quantity' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productionSchedules(): HasMany
    {
        return $this->hasMany(ProductionSchedule::class);
    }
}