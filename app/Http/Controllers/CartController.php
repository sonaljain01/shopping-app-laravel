<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Product;
use Auth;
use App\Models\Menu;
use DB;
class CartController extends Controller
{
    public function addToCart(Request $request, $productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // Get the selected country from the session
        $country = session('country', 'IN'); // Default country is 'IN' if not set
        $exchangeRate = getExchangeRate($country);

        if (!$exchangeRate['status']) {
            return redirect()->back()->with('error', 'Failed to retrieve exchange rate.');
        }

        $currency = $exchangeRate['currency'];
        $conversionRate = $exchangeRate['data'];

        if (!session()->has('cart')) {
            session()->put('cart', []);
        }

        $guestId = session()->getId();
        $userId = auth()->check() ? auth()->id() : null;

        $cart = session()->get('cart');

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                'product_id' => $product->id,
                'title' => $product->title,
                'price' => round($product->price * $conversionRate, 2), // Convert price
                'quantity' => 1,
                'image' => $product->product_images->isNotEmpty() ? $product->product_images[0]->image : 'path/to/default/image.jpg',
                'currency' => $currency,
            ];
        }

        session()->put('cart', $cart);

        $cartItem = Cart::where('product_id', $productId)
            ->where(function ($query) use ($userId, $guestId) {
                $query->where('user_id', $userId)
                    ->orWhere('guest_id', $guestId);
            })
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            Cart::create([
                'user_id' => $userId,
                'guest_id' => $userId ? null : $guestId,
                'product_id' => $productId,
                'quantity' => 1,
                'currency' => $currency, // Save dynamic currency
                'price' => round($product->price * $conversionRate, 2), // Save converted price
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully.');
    }

    
    public function viewCart()
    {
        $cartItems = session()->get('cart', []);
        $forexMode = DB::table('settings')->where('key', 'forex_mode')->value('value') ?? 'auto';
        $country = session('country', 'IN'); // Default country
        $exchangeRate = null;

        if ($forexMode === 'manual') {
            // Fetch manual exchange rate from the database
            $baseCurrency = 'INR'; // Default base currency
            $targetCurrency = getCurrencyCodeFromCountry($country); // Helper function to get currency code
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
                $exchangeRate = [
                    'status' => true,
                    'data' => 1, // Default rate
                    'currency' => '₹', // Default currency symbol
                ];
            }
        } else {
            // Use API-based logic to fetch exchange rate
            $exchangeRate = getExchangeRate($country);
        }

        foreach ($cartItems as $productId => $item) {
            $product = Product::find($productId);

            if ($product) {
                $taxType = $product->tax_type ?? 'no_tax';
                $taxPrice = $product->tax_price ?? 0;

                // Calculate updated price based on forex
                $convertedPrice = $product->price * (float) $exchangeRate['data'];

                $cartItems[$productId]['price'] = round($convertedPrice, 2);
                $cartItems[$productId]['currency'] = $exchangeRate['currency'] ?? '₹';
                $cartItems[$productId]['tax_type'] = $taxType;
                $cartItems[$productId]['tax_price'] = $taxPrice;

                // Calculate tax
                if ($taxType === 'inclusive') {
                    $cartItems[$productId]['tax'] = $convertedPrice - ($convertedPrice / (1 + ($taxPrice / 100)));
                } elseif ($taxType === 'exclusive') {
                    $cartItems[$productId]['tax'] = $convertedPrice * ($taxPrice / 100);
                } else {
                    $cartItems[$productId]['tax'] = 0;
                }
            }
        }

        // Save updated cart items in the session
        session()->put('cart', $cartItems);

        $cartItemsCount = count($cartItems);

        // Fetch header and footer menus
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
            ->whereNull('parent_id')
            ->where('status', 1)
            ->where(function ($query) {
                $query->where('location', 'header')
                    ->orWhere('location', 'both');
            })
            ->get();

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
            ->where('status', 1)
            ->where(function ($query) {
                $query->where('location', 'footer')
                    ->orWhere('location', 'both');
            })
            ->get();

        return view('front.cart', compact('cartItems', 'cartItemsCount', 'headerMenus', 'footerMenus'));
    }





    public function update(Request $request)
    {
        $cart = session()->get('cart');
        $userId = auth()->check() ? auth()->id() : null;
        $guestId = session()->getId();

        // Handle item removal from the cart
        if ($request->has('remove_item')) {
            $productId = $request->input('remove_item');

            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                session()->put('cart', $cart);

                // Remove from the database
                $idToUse = $userId ?? $guestId;

                if ($idToUse) {
                    Cart::where('product_id', $productId)
                        ->where(function ($query) use ($userId, $guestId) {
                            $query->where('user_id', $userId)
                                ->orWhere('guest_id', $guestId);
                        })
                        ->delete();
                }

                return response()->json(['success' => true, 'message' => 'Product removed from cart successfully!']);
            }
        }

        // Handle quantity updates
        if ($request->has('quantities')) {
            foreach ($request->input('quantities') as $productId => $quantity) {
                if (isset($cart[$productId])) {
                    // Update quantity in session cart
                    $cart[$productId]['quantity'] = $quantity;
                }
            }

            // Save the updated cart back to the session
            session()->put('cart', $cart);

            // Update quantities in the database
            foreach ($request->input('quantities') as $productId => $quantity) {
                $idToUse = $userId ?? $guestId;

                if ($idToUse) {
                    Cart::updateOrCreate(
                        ['product_id' => $productId, 'user_id' => $userId, 'guest_id' => $guestId],
                        ['quantity' => $quantity]
                    );
                }
            }

            return redirect()->back()->with('success', 'Product quantity updated successfully!');
        }

        return redirect()->back()->with('error', 'Product not found in cart!');
    }
}
