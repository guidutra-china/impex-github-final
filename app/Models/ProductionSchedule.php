<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionSchedule extends Model
{
    protected $fillable = [
        "order_item_id",
        "scheduled_date",
        "quantity_scheduled",
    ];

    protected $casts = [
        "scheduled_date" => "date",
        "quantity_scheduled" => "integer",
    ];

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }
}