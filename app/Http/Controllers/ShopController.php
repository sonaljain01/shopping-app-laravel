<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
class ShopController extends Controller
{

    public function index(Request $request, $categorySlug = null)
    {
        $categorySelected = null;
        $subCategorySelected = null;

        $categories = Category::with('children')->where('status', 1)->orderBy('name', 'ASC')->get();
        $brands = Brand::where('status', 1)->orderBy('name', 'ASC')->get();

        $productsQuery = Product::where('status', 1);

        if (!empty($categorySlug)) {    
            $category = Category::where('slug', $categorySlug)->where('status', 1)->first();

            if ($category) {
                if ($category->parent_id) {
                    $subCategorySelected = $category->id;
                    $categorySelected = $category->parent_id;
                    $productsQuery->where('category_id', $category->id);
                } else {
                    $categorySelected = $category->id;
                    $productsQuery->where('category_id', $category->id);
                }
            }
        }

        if ($request->has('brand')) {
            $brandIds = $request->input('brand', []);
            if (!empty($brandIds)) {
                $productsQuery->whereIn('brand_id', $brandIds);
            }
        }

        if ($request->has('price')) {
            $priceRange = explode('-', $request->input('price'));
            if (count($priceRange) == 2) {
                $minPrice = $priceRange[0];
                $maxPrice = $priceRange[1];
                $productsQuery->whereBetween('price', [$minPrice, $maxPrice]);
            }
        }

        $products = $productsQuery->orderBy('id', 'desc')->get();

        return view('front.shop', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
            'categorySelected' => $categorySelected,
            'subCategorySelected' => $subCategorySelected,
        ]);
    }

}
