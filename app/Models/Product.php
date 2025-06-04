<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\{HasSlug, SlugOptions};
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasSlug, SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'image',
        'image_mime',
        'image_size',
        'published',
        'created_by',
        'updated_by'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
