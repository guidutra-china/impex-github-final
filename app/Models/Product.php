<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'sku_client',
        'sku_supplier',
        'hscode',
        'ncm',
        'cost',
        'price',
        'currency',
        'familyproducts_id'
    ];

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }
}
