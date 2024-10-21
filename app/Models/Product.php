<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'price',
        'sku',
        'track_qty',
        'qty',
        'category_id',
        'brand_id',
        'description',
        'is_featured',
        'image_array',
        'image',
        'additional_image',
    ];
    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }

    

}
