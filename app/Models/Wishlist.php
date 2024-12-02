<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'guest_id',
        'currecy_code',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    
}
