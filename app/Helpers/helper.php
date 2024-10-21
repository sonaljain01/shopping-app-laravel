<?php
use App\Models\Category;

function getCategories()
{
    // Category::orderBy('name', 'ASC')->get();
    return Category::all()->where('showHome', 'Yes');
}
