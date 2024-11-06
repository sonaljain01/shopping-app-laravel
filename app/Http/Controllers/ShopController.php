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

        // Initialize the product query with status = 1 (active products)
        $productsQuery = Product::where('status', 1);

        // Apply category filter if categorySlug is provided
        if ($categorySlug) {
            $this->applyCategoryFilter($productsQuery, $categorySlug);
        }

        // Apply brand filter if brand is provided
        if ($request->has('brand')) {
            $this->applyBrandFilter($productsQuery, $request->input('brand'));
        }

        // Apply price filter if price range is selected
        // $this->applyPriceFilter($productsQuery, $request);

        // Apply sorting based on user's selection
        $this->applySorting($productsQuery, $request->input('sort'));

        // Fetch the filtered products, ensuring we always have an empty array if no products are found
        $products = $productsQuery->get();

        // If no products are found, return an empty collection
        if ($products->isEmpty()) {
            $products = collect(); // This ensures $products is always defined, even if empty
        }

        // If the request is an AJAX request, return a partial view with the products
        if ($request->ajax()) {
            return view('front.shop', [
                'products' => $products,
            ]);
        }

        // For non-AJAX requests, return the full page view
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
        // Ensure that brand filter is applied when one or more brand IDs are provided
        if (!empty($brandIds)) {
            // If it's an array of brands, apply whereIn
            $query->whereIn('brand_id', (array) $brandIds);
        }
    }

    // protected function applyPriceFilter($query, Request $request)
    // {
    //     $minPrice = floatval($request->input('price_min', 0));
    //     $maxPrice = floatval($request->input('price_max', PHP_INT_MAX));

    //     // Apply price filter only if valid min and max prices are given
    //     if ($minPrice >= 0 && $minPrice < $maxPrice) {
    //         $query->whereBetween('price', [$minPrice, $maxPrice]);
    //     }
    // }

    protected function applySorting($query, $sort)
    {
        // Apply sorting based on the user's selection
        switch ($sort) {
            case 'price_low_high':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high_low':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');  // Ensure 'rating' field exists on the products table
                break;
            case 'trending':
                $query->orderBy('trending_score', 'desc');  // Ensure 'trending_score' field exists
                break;
            default:
                $query->orderBy('id', 'desc');  // Default to sorting by product ID (newest first)
                break;
        }
    }

    public function filterByPrice(Request $request)
    {
        $validated = $request->validate([
            'min' => 'required|numeric|min:0',
            'max' => 'required|numeric|min:0',
        ]);

        // Get the price range
        $minPrice = $validated['min'];
        $maxPrice = $validated['max'];

        // Fetch filtered products based on the price range
        $products = Product::whereBetween('price', [$minPrice, $maxPrice])->get();

        // Return response in JSON format
        return response()->json([
            'product' => [
                'data' => $products
            ]
        ]);
    }

    public function show($slug)
    {
        // Find the product by slug, ensuring it exists
        $product = Product::where('slug', $slug)->firstOrFail();

        // Return a view and pass the product data to it
        return view('product.show', compact('product'));
    }
}
