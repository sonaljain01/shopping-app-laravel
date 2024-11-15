<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Menu;
class FrontController extends Controller
{
    public function index(Request $request)
    {


        $categories = Category::with('children')->where('status', 1)->orderBy('name', 'ASC')->get();
        $brands = Brand::where('status', 1)->orderBy('name', 'ASC')->get();

        $productsQuery = Product::where('status', 1);



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
        // Pass data to the view
        return view('front.shop', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
            'headerMenus' => $headerMenus,   // Pass header menus to the view
            'footerMenus' => $footerMenus,   // Pass footer menus to the view
        ]);

    }

 
}