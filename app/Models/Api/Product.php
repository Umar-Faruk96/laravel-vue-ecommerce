<?php

namespace App\Models\Api;

use App\Models\Product as MainProduct;


/**
 * @property mixed $images
 */
class Product extends MainProduct
{
    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
