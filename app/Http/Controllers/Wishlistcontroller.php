<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Models\Menu;
class Wishlistcontroller extends Controller
{
    // Method to add a product to the wishlist
    public function add(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            // 'currency_code' => 'required|exists:currencies,code',
        ]);

        // Get user ID if authenticated, else use guest ID from session
        $userId = auth()->check() ? auth()->user()->id : null;
        $guestId = auth()->check() ? null : session('guest_id');

        // Check for existing wishlist entry to avoid duplicates
        $existingWishlistItem = Wishlist::where('product_id', $validatedData['product_id'])
            ->where(function ($query) use ($userId, $guestId) {
                $query->where('user_id', $userId)->orWhere('guest_id', $guestId);
            })
            ->first();

        // If the item doesn't exist in the wishlist, create it
        if (!$existingWishlistItem) {
            Wishlist::create([
                'product_id' => $validatedData['product_id'],
                'user_id' => $userId,
                'guest_id' => $guestId,
                'currency_code' => $request->currency,
            ]);
            return redirect()->back()->with('success', 'Product added to wishlist successfully!');
        }

        return redirect()->back()->with('info', 'Product is already in your wishlist.');
    }

    // Method to view the wishlist
    public function index()
    {
        // Only authenticated users can view their wishlist
        if (!auth()->check()) {
            return redirect()->route('front.register')->with('alert', 'Please login first to view your wishlist.');
        }

        // Fetch the authenticated user's wishlist
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->with('product')
            ->get();

        $wishlistCount = $wishlists->count(); // Count items in the user's wishlist
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
        return view('front.wishlist', compact('wishlists', 'wishlistCount', 'headerMenus', 'footerMenus'));
    }

    // Method to remove an item from the wishlist
    public function remove($id)
    {
        // Find the wishlist item by ID
        $wishlist = Wishlist::find($id);

        // Ensure the user is authorized to remove the item
        if (!$wishlist || $wishlist->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'You are not authorized to remove this item from your wishlist.');
        }

        // Delete the wishlist item
        $wishlist->delete();
        return redirect()->back()->with('success', 'Product removed from wishlist successfully.');
    }

    // Method to get the wishlist count for the authenticated user or guest
    public function getWishlistCount()
    {
        if (auth()->check()) {
            // Count items for authenticated users
            return Wishlist::where('user_id', auth()->id())->count();
        } else {
            // Count items for guests
            return Wishlist::where('guest_id', session('guest_id'))->count();
        }
    }
}
