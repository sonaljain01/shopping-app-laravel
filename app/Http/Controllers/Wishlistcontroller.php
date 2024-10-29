<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class Wishlistcontroller extends Controller
{
    public function add(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Wishlist::create([
            'product_id' => $validatedData['product_id'],
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'guest_id' => auth()->check() ? null : session('guest_id'),
        ]);

        return redirect()->back()->with('success', 'Product added to wishlist successfully!');
    }

    public function index()
    {
        $wishlists = Wishlist::where('user_id', auth()->user()->id)
            ->orWhere('guest_id', session('guest_id'))
            ->with('product')
            ->get();

        return view('front.wishlist', compact('wishlists'));
    }
}
