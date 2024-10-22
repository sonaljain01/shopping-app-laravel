<?php
use App\Models\Category;

function getCategories()
{
    // Category::orderBy('name', 'ASC')->get();
    return Category::with('children')->where('showHome', 'Yes')->orderBy('id', 'desc')->get();
}
