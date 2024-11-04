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

        $userId = auth()->check() ? auth()->user()->id : null;
        $guestId = auth()->check() ? null : session('guest_id');

        // Check for existing wishlist entry to avoid duplicates
        $existingWishlistItem = Wishlist::where('product_id', $validatedData['product_id'])
            ->where(function ($query) use ($userId, $guestId) {
                $query->where('user_id', $userId)->orWhere('guest_id', $guestId);
            })
            ->first();

        if (!$existingWishlistItem) {
            Wishlist::create([
                'product_id' => $validatedData['product_id'],
                'user_id' => $userId,
                'guest_id' => $guestId,
            ]);
        }

        return redirect()->back()->with('success', 'Product added to wishlist successfully!');
    }

    public function index()
    {
        $wishlists = Wishlist::where('guest_id', session('guest_id'))
            // ->orWhere('guest_id', session('guest_id'))
            ->with('product')
            ->get();

        return view('front.wishlist', compact('wishlists'));
    }

    public function remove($id)
    {
        $wishlist = Wishlist::find($id);
        $wishlist->delete();

        return redirect()->back()->with('success', 'product removed form wishlist');
    }

    public function getWishlistCount()
    {
        $wishlistCount = Wishlist::where('user_id', auth()->id())
            ->orWhere('guest_id', session('guest_id'))
            ->count();

        return $wishlistCount;
    }
}
