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
        // $user = Auth::user();
        if (!session()->has('cart')) {
            session()->put('cart', []);
        }

        // $cartItem = Cart::where('user_id', $user->id)->where('product_id', $product->id)->first();

        // if($cartItem){
        //     $cartItem->increment('quantity');
        //     $cartItem->save();  
        // }else{
        //     Cart::create([
        //         'user_id' => $user->id,
        //         'product_id' => $product->id,
        //         'quantity' => 1
        //     ]);
        // }
        $cart = session()->get('cart');

        // Check if the product is already in the cart
        if (array_key_exists($productId, $cart)) {
            // If it exists, increment the quantity
            $cart[$productId]['quantity']++;
        } else {
            // If it doesn't exist, add it to the cart
            $cart[$productId] = [
                'title' => $product->title,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->product_images->isNotEmpty() ? $product->product_images[0]->image : 'path/to/default/image.jpg',
            ];
        }

        // Update the cart in the session
        session()->put('cart', $cart);
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
