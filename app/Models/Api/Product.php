<?php

namespace App\Models\Api;

use App\Models\Product as MainProduct;


class Product extends MainProduct
{
    public function getRouteKeyName()
    {
        return 'id';
    }
}
