<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Product;
use Auth;
use App\Models\Menu;
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

        // Initialize the session cart if it doesn't exist
        if (!session()->has('cart')) {
            session()->put('cart', []);
        }

        // Generate a unique guest ID if the user is not authenticated
        $guestId = session()->getId();
        $userId = auth()->check() ? auth()->id() : null;

        // Get the current cart in the session
        $cart = session()->get('cart');

        // Check if product exists in the session cart
        if (isset($cart[$productId])) {
            // Increment the quantity if it exists
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

        // Save the updated cart in the session
        session()->put('cart', $cart);

        // Database operations for adding to cart
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




    // public function viewCart()
    // {
    //     $cartItems = session()->get('cart', []);
    //     $cartItemsCount = count($cartItems);
    //     $headerMenus = Menu::with([
    //         'children' => function ($query) {
    //             $query->where('status', 1)
    //                 ->with([
    //                     'children' => function ($query) {
    //                         $query->where('status', 1)
    //                             ->with([
    //                                 'children' => function ($query) {
    //                                     $query->where('status', 1);
    //                                 }
    //                             ]);
    //                     }
    //                 ]);
    //         }
    //     ])
    //         ->whereNull('parent_id') // Ensure only top-level menus are fetched
    //         ->where('status', 1) // Only include menus with status = 1
    //         ->where(function ($query) {
    //             $query->where('location', 'header')
    //                 ->orWhere('location', 'both');
    //         })
    //         ->get();

    //     // Optionally, for the footer menus, you can follow the same approach
    //     $footerMenus = Menu::with([
    //         'children' => function ($query) {
    //             $query->where('status', 1)
    //                 ->with([
    //                     'children' => function ($query) {
    //                         $query->where('status', 1)
    //                             ->with([
    //                                 'children' => function ($query) {
    //                                     $query->where('status', 1);
    //                                 }
    //                             ]);
    //                     }
    //                 ]);
    //         }
    //     ])
    //         ->whereNull('parent_id')
    //         ->where('status', 1) // Only include menus with status = 1
    //         ->where(function ($query) {
    //             $query->where('location', 'footer')
    //                 ->orWhere('location', 'both');
    //         })
    //         ->get();

    //     return view('front.cart', compact('cartItems', 'cartItemsCount', 'headerMenus', 'footerMenus'));
    // }


    public function viewCart()
    {
        $cartItems = session()->get('cart', []);
        foreach ($cartItems as $productId => $item) {
            if (!isset($item['currency'])) {
                $cartItems[$productId]['currency'] = '₹'; // Default currency symbol
            }
        }
        session()->put('cart', $cartItems); // Update session with fixed cart items

        $cartItemsCount = count($cartItems);

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
