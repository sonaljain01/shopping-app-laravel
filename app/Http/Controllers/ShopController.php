<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\SearchKeyword;
use App\Models\Menu;
class ShopController extends Controller
{


    public function index(Request $request, $categorySlug = null)
    {
        $categories = $this->getActiveCategories();  // Get active categories
        $brands = $this->getActiveBrands();  // Get active brands

        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');

            // Check if the keyword exists in the database
            $existingKeyword = SearchKeyword::where('keyword', $keyword)->first();

            if ($existingKeyword) {
                // If it exists, increment the count
                $existingKeyword->increment('count');
            } else {
                // If it doesn't exist, create a new record
                SearchKeyword::create([
                    'keyword' => $keyword,
                    'count' => 1
                ]);
            }
        }

        $productsQuery = Product::where('status', 1);

        if ($categorySlug) {
            $this->applyCategoryFilter($productsQuery, $categorySlug);
        }

        if ($request->has('brand')) {
            $this->applyBrandFilter($productsQuery, $request->input('brand'));
        }

        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $productsQuery->where('title', 'LIKE', '%' . $keyword . '%');
        }

        $this->applySorting($productsQuery, $request->input('sort'));



        $products = $productsQuery->get();
        if ($request->ajax()) {
            return response()->json(['products' => $products]);
        }
        if ($products->isEmpty()) {
            $products = collect(); // Ensure $products is always defined, even if empty
        }

        $headerMenus = Menu::with([
            'children' => function ($query) {
                $query->where('status', 1)
                    ->with([
                        'children' => function ($query) {
                            $query->where('status', 1)
                                ->with([
                                    'children' => function ($query) {
                                        $query->where('status', 1);
                                    }
                                ]);
                        }
                    ]);
            }
        ])
            ->whereNull('parent_id') // Ensure only top-level menus are fetched
            ->where('status', 1) // Only include menus with status = 1
            ->where(function ($query) {
                $query->where('location', 'header')
                    ->orWhere('location', 'both');
            })
            ->get();

        // Optionally, for the footer menus, you can follow the same approach
        $footerMenus = Menu::with([
            'children' => function ($query) {
                $query->where('status', 1)
                    ->with([
                        'children' => function ($query) {
                            $query->where('status', 1)
                                ->with([
                                    'children' => function ($query) {
                                        $query->where('status', 1);
                                    }
                                ]);
                        }
                    ]);
            }
        ])
            ->whereNull('parent_id')
            ->where('status', 1) // Only include menus with status = 1
            ->where(function ($query) {
                $query->where('location', 'footer')
                    ->orWhere('location', 'both');
            })
            ->get();
        $ip = '146.70.245.84';
        $data = getLocationInfo($ip);
        $telcode = $data['data']['country'] ?? 'IN';
        return view('front.shop', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
            'categorySelected' => $request->input('categorySelected'),
            'subCategorySelected' => $request->input('subCategorySelected'),
            'sort' => $request->input('sort'),
            // 'keyword' => $keyword ?? null,
            'headerMenus' => $headerMenus,   // Pass header menus to the view
            'footerMenus' => $footerMenus,
            'telcode' => $telcode

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

    public function showProducts()
    {
        // Get the minimum and maximum prices dynamically
        $minPrice = Product::min('price') ?? 0;
        $maxPrice = Product::max('price') ?? 1000;

        // Fetch initial products, categories, and brands
        $products = Product::all();
        $categories = $this->getActiveCategories();
        $brands = $this->getActiveBrands();

        return view('front.shop', [
            'categories' => $categories,
            'products' => $products,
            'brands' => $brands,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
        ]);
    }

    public function filter(Request $request)
    {
        $priceRange = $request->input('price_range');
        // $size = $request->input('size');

        $products = Product::query()->select('id', 'title', 'slug', 'description', 'price', 'compare_price', 'category_id', 'brand_id', 'sku');

        if ($priceRange && strpos($priceRange, '-') !== false) {
            list($minPrice, $maxPrice) = explode('-', $priceRange);
            // dd($minPrice, $maxPrice);
            if (is_numeric($minPrice) && is_numeric($maxPrice)) {
                $products = Product::whereBetween('price', [(float) $minPrice, (float) $maxPrice]);
            } else {
                return redirect()->back()->with('error', 'Invalid price range.');
            }
        } else {
            return redirect()->back()->with('error', 'Please select a valid price range.');
        }
        $headerMenus = Menu::with([
            'children' => function ($query) {
                $query->where('status', 1)
                    ->with([
                        'children' => function ($query) {
                            $query->where('status', 1)
                                ->with([
                                    'children' => function ($query) {
                                        $query->where('status', 1);
                                    }
                                ]);
                        }
                    ]);
            }
        ])
            ->whereNull('parent_id') // Ensure only top-level menus are fetched
            ->where('status', 1) // Only include menus with status = 1
            ->where(function ($query) {
                $query->where('location', 'header')
                    ->orWhere('location', 'both');
            })
            ->get();

        // Optionally, for the footer menus, you can follow the same approach
        $footerMenus = Menu::with([
            'children' => function ($query) {
                $query->where('status', 1)
                    ->with([
                        'children' => function ($query) {
                            $query->where('status', 1)
                                ->with([
                                    'children' => function ($query) {
                                        $query->where('status', 1);
                                    }
                                ]);
                        }
                    ]);
            }
        ])
            ->whereNull('parent_id')
            ->where('status', 1) // Only include menus with status = 1
            ->where(function ($query) {
                $query->where('location', 'footer')
                    ->orWhere('location', 'both');
            })
            ->get();


        $products = $products->get();

        $minPrice = Product::min('price');
        $maxPrice = Product::max('price');

        $categories = $this->getActiveCategories();
        $brands = $this->getActiveBrands();

        return view('front.shop', [
            'categories' => $categories,
            'products' => $products,
            'brands' => $brands,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'headerMenus' => $headerMenus,
            'footerMenus' => $footerMenus
        ]);
    }

    public function getPopularSearchKeywordsForModal()
    {
        // Fetch the top 10 most popular search keywords
        $popularKeywords = SearchKeyword::orderBy('count', 'desc')
            ->take(10) // Top 10 results
            ->get(['keyword']); // Only return the keyword column

        // Return the keywords as a JSON response
        return response()->json($popularKeywords);
    }


}
