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
        $categories = $this->getActiveCategories();
        $brands = $this->getActiveBrands();
        
        $productsQuery = Product::where('status', 1);
        
        if ($categorySlug) {
            $this->applyCategoryFilter($productsQuery, $categorySlug);
        }

        if ($request->has('brand')) {
            $this->applyBrandFilter($productsQuery, $request->input('brand'));
        }

        $this->applyPriceFilter($productsQuery, $request);
        
        $this->applySorting($productsQuery, $request->input('sort'));

        $products = $productsQuery->get();
        if ($request->ajax()) {
            return view('front.shop', [
                'products' => $products,
            ]);
        }
        return view('front.shop', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
            'categorySelected' => $request->input('categorySelected'), 
            'subCategorySelected' => $request->input('subCategorySelected'), 
            'sort' => $request->input('sort'),
        ]);
    }

    protected function getActiveCategories()
    {
        return Category::with('children')->where('status', 1)->orderBy('name', 'ASC')->get();
    }

    protected function getActiveBrands()
    {
        return Brand::where('status', 1)->orderBy('name', 'ASC')->get();
    }

    protected function applyCategoryFilter($query, $categorySlug)
    {
        $category = Category::where('slug', $categorySlug)->where('status', 1)->first();
        if ($category) {
            $query->where('category_id', $category->id);
        }
    }

    protected function applyBrandFilter($query, $brandIds)
    {
        if (!empty($brandIds)) {
            $query->whereIn('brand_id', $brandIds);
        }
    }

    protected function applyPriceFilter($query, Request $request)
    {
        $minPrice = floatval($request->input('price_min', 0));
        $maxPrice = floatval($request->input('price_max', PHP_INT_MAX));

        if ($minPrice >= 0 && $minPrice <= $maxPrice) {
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        }
    }

    protected function applySorting($query, $sort)
    {
        switch ($sort) {
            case 'price_low_high':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high_low':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'trending':
                $query->orderBy('trending_score', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
                break;
        }
    }
}
