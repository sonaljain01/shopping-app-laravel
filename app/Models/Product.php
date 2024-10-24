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
        'image',
        'compare_price',
        'additional_image',
    ];
    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function subCategory()
    {
    return $this->belongsTo(Category::class, 'category_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}
