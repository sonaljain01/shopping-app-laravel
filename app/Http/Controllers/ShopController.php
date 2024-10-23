<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
class ShopController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name', 'ASC')->with('children')->where('status', 1)->get();
        $brands = Brand::orderBy('name', 'ASC')->where('status', 1)->get();
        $products = Product::orderBy('id', 'desc')->where('status', 1)->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;
        return view('front.shop', $data);
    }
}
