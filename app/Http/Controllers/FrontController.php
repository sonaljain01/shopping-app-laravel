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
use DB;
class FrontController extends Controller
{
    public function index(Request $request)
    {
        // Get the forex mode from the database
        $forexMode = DB::table('settings')->where('key', 'forex_mode')->value('value') ?? 'auto';

        $country = session('country', 'IN');
        $exchangeRate = null;

        if ($forexMode === 'manual') {
            // Fetch the manual rate from the database
            $baseCurrency = 'INR'; // Default base currency
            $targetCurrency = getCurrencyCodeFromCountry($country); // Helper to map country to currency
            $manualRate = DB::table('forex_rates')
                ->where('base_currency', $baseCurrency)
                ->where('target_currency', $targetCurrency)
                ->first();

            if ($manualRate) {
                $exchangeRate = [
                    'status' => true,
                    'data' => $manualRate->rate,
                    'currency' => $manualRate->currency_symbol,
                ];
            } else {
                // Fallback if no manual rate found
                $exchangeRate = [
                    'status' => true,
                    'data' => 1, // Default rate
                    'currency' => '₹', // Default currency symbol
                ];
            }
        } else {
            // Fetch the rate using the existing API-based logic
            $exchangeRate = getExchangeRate($country);
        }

        // Rest of your code for fetching categories, brands, products, etc.
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
            $price = (float) $product->price;
            $calculatedPrice = $price * (float) $exchangeRate['data'];

            $product->price = round($calculatedPrice, 2);
            $product->currency = $exchangeRate['currency'] ?? '₹';

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
        // Pass data to the view
        return view('front.shop', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
            'exchangeRate' => $exchangeRate,
            'headerMenus' => $headerMenus,
            'footerMenus' => $footerMenus,
            'telcode' => $telcode
        ]);
    }


}