<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use  App\Models\AttributeValue;
class Attribute extends Model
{
    protected $fillable = [
        'name',
    ];

    public function values(){
        return $this->hasMany(AttributeValue::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attributes')
            ->withPivot('attribute_value_id')
            ->withTimestamps();
    }
}
