<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\State;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Menu;
use App\Models\City;
use App\Models\ForexRate;
class FrontController extends Controller
{
    public function index(Request $request)
    {
        $country = session('country');
        $exchangeRate = getExchangeRate($country);

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
        $products->transform(function ($product) use ($exchangeRate) {
            $product->price = round($product->price * $exchangeRate['data'], 2);
            $product->currency = $exchangeRate['currency'];
            $product->cost_price = round($product->cost_price * $exchangeRate['data'], 2);
            return $product;
        });
        $headerMenus = Menu::with([
            'children' => function ($query) {
                $query->where('status', 1)
                    ->orderBy('order', 'asc')
                    ->with([
                        'children' => function ($query) {
                            $query->where('status', 1)
                                ->orderBy('order', 'asc')
                                ->with([
                                    'children' => function ($query) {
                                        $query->where('status', 1)
                                            ->orderBy('order', 'asc');
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

            ->orderBy('order', 'asc')
            ->get();

        // Optionally, for the footer menus, you can follow the same approach
        $footerMenus = Menu::with([
            'children' => function ($query) {
                $query->where('status', 1)
                    ->orderBy('order', 'asc')
                    ->with([
                        'children' => function ($query) {
                            $query->where('status', 1)
                                ->orderBy('order', 'asc')
                                ->with([
                                    'children' => function ($query) {
                                        $query->where('status', 1)
                                            ->orderBy('order', 'asc');
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
            ->orderBy('order', 'asc')
            ->get();
        

        $telcode = 'IN';
        if (!session()->has('country')) {
            if (auth()->check()) {
                session()->put('country', auth()->user()->country);
            } else {
                $ip = request()->ip() ?? '146.70.245.84';
                $data = getLocationInfo($ip);
                $country = $data['data']['country'] ?? $telcode;
                session()->put('country', $country);
            }
        }

        $telcode = session('country', 'IN');


        // Get conversion rate

        // Pass data to the view
        return view('front.shop', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
            'headerMenus' => $headerMenus,  
            'footerMenus' => $footerMenus,  
            'telcode' => $telcode,
            'exchangeRate' => $exchangeRate
        ]);

    }


}