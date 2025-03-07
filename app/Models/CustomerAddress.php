<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'present_address', 'permanent_address', 'city', 'state', 'zip_code', 'country_code', 'customer_id'];

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'user_id', 'customer_id');
    }
}
