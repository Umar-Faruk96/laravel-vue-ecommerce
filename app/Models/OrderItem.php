<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Relations\BelongsTo, Factories\HasFactory};

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'unit_price', 'product_id', 'quantity'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
