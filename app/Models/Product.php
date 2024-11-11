<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductAttribute;
use App\Models\AttributeValue;
use App\Models\Attribute;
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
        'compare_price',
        'status',
        'barcode',
        

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

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes')
            ->withPivot('attribute_value_id')
            ->withTimestamps();
    }


}
