<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'slug', 
        'status', 
        'showHome', 
        'is_subcategory', 
        'parent_id'
    ];

    // Relation to parent category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relation to child categories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
