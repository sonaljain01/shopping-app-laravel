<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Product;
use Auth;

class CartController extends Controller
{
    public function addToCart(Request $request, $productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

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
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->product_images->isNotEmpty() ? $product->product_images[0]->image : 'path/to/default/image.jpg',
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
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully');
    }

    public function viewCart()
    {
        $cartItems = session()->get('cart', []);
        $cartItemsCount = count($cartItems);
        return view('front.cart', compact('cartItems', 'cartItemsCount'));
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
