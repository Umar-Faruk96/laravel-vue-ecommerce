<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, Relations\HasOne};

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'status', 'gateway', 'amount', 'type', 'session_id', 'created_by', 'updated_by'];

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }
}
