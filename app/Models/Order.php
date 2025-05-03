<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'client_company_id',
        'supplier_company_id',
        'payment_id',
        'order_date',
        'origen',
        'destination',
        'client_number',
        'supplier_number',
        'total_price', // Ensure this is fillable if calculated/stored
        'discount',
        'net_weight',
        'gross_weight',
    ];

    protected $casts = [
        'order_date' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'client_company_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'supplier_company_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessor to calculate total price if not stored directly
    // public function getTotalPriceAttribute(): int
    // {
    //     return $this->orderItems()->sum('total_price');
    // }
}
