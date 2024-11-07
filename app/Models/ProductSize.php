<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
class ProductSize extends Model
{
    protected $fillable = [
        'product_id',
        'size',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
