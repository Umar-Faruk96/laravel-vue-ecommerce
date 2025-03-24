<?php

namespace App\Models;

use Illuminate\Database\{Eloquent\Model, Eloquent\Factories\HasFactory};

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'quantity'];
}
