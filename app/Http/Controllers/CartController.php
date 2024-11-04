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
            // Add new product to the session cart if it doesn't exist
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

        
        $cartItem = Cart::where('product_id', $productId)
            ->where(function ($query) use ($userId, $guestId) {
                $query->where('user_id', $userId)
                    ->orWhere('guest_id', $guestId);
            })
            ->first();

        if ($cartItem) {
            // Increment quantity if the item is already in the database cart
            $cartItem->increment('quantity');
        } else {
            // If the item is not in the database cart, create a new entry
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
        // $cartItems = Cart::with('products')->where('user_id', Auth::user()->id)->get();
        $cartItems = session()->get('cart', []);
        return view('front.cart', compact('cartItems'));
    }

    public function removeFromCart($productId)
    {
        $cart = session()->get('cart');

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Product removed from cart successfully!');
    }
}
