<?php

namespace App\Models\Api;

use App\Models\Product as MainProduct;


class Product extends MainProduct
{
    public mixed $images;

    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
