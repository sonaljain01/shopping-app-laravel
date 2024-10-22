<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        $products = Product::where('is_featured', 'Yes')->orderBy('id', 'desc')->take(4)->where('status', 1)->get();
        $data['featuredProducts'] = $products;

        $latestProducts = Product::orderBy('id', 'desc')->where('status', 1)->take(4)->get();
        $data['latestProducts'] = $latestProducts;
        return view('front.home', $data);
    }
}
