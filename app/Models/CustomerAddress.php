<?php

namespace App\Models;

use Illuminate\{Database\Eloquent\Model, Database\Eloquent\Relations\BelongsTo, Database\Eloquent\Factories\HasFactory};

class CustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'house_number', 'area', 'city', 'state', 'zip_code', 'country_code', 'customer_id'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'user_id', 'customer_id');
    }
}
