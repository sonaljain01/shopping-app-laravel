<?php

namespace App\Http\Controllers;

use App\Models\BillingAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Models\Product;
use DB;
use App\Models\User;
use Hash;
use Auth;
class OrderController extends Controller
{
    public function checkout()
    {
        $cartItems = session('cart', []); // Retrieve items from cart session
        // $billingDetails = auth()->user()->billingAddress; // Assuming billing address is saved with the user profile
        $products = Product::whereIn('id', array_keys($cartItems))->get();
        $subtotal = 0;
        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'] ?? 1;
            $subtotal += $product->price * $quantity;
        }

        // Calculate tax (e.g., 10% of subtotal)
        $taxRate = 0.10;
        $tax = $subtotal * $taxRate;

        // Calculate total
        $total = $subtotal + $tax;
        $paymentMethods = PaymentMethod::all();
        return view('front.checkout', compact('cartItems', 'products', 'subtotal', 'tax', 'total', 'paymentMethods'));

    }

    public function placeOrder(Request $request)
    {
        // dd(session('cart'));
        $validatedData = $request->validate([
            'username' => 'required|string',
            'email' => 'required|email',
            'company' => 'nullable',
            'address_1' => 'required',
            'address_2' => 'nullable',
            'city' => 'required',
            'zip' => 'required',
            'phone' => 'required|numeric',
            'additional_information' => 'nullable',
            'payment_method' => 'required|in:cod,razorpay',
            'country' => 'required',
        ]);

        DB::transaction(function () use ($validatedData) {
            if (isset($validatedData['create_account']) && $validatedData['create_account']) {
                // Check if the email is already registered
                $existingUser = User::where('email', $validatedData['email'])->first();
                if ($existingUser) {
                    throw new \Exception('Email already exists. Please log in or use a different email.');
                }

                // Create the new user account
                $user = User::create([
                    'username' => $validatedData['username'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
                ]);

                // Log in the new user
                Auth::login($user);
            }
            // Create Billing Address
            $billingAddress = BillingAddress::create([
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'company' => $validatedData['company'],
                'address_1' => $validatedData['address_1'],
                'address_2' => $validatedData['address_2'],
                'city' => $validatedData['city'],
                'zip' => $validatedData['zip'],
                'phone' => $validatedData['phone'],
                'additional_information' => $validatedData['additional_information'],
                'country' => $validatedData['country'],
            ]);

            $cartItems = session('cart', []);
            $totalAmount = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);

            $order = Order::create([
                'user_id' => auth()->id(),
                'guest_id' => session('guest_id'),
                'billing_address_id' => $billingAddress->id,
                'payment_method' => $validatedData['payment_method'],
                'status' => 'In Progress',
                'total_amount' => $totalAmount,
                'phone' => $validatedData['phone'],
            ]);

            // Debugging for order creation
            if (!$order || !$order->id) {
                \Log::error('Order creation failed', [
                    'order' => $order,
                    'billing_address' => $billingAddress,
                ]);
                throw new \Exception('Order creation failed');
            }

            foreach ($cartItems as $cartItem) {
                if (!isset($cartItem['product_id'])) {
                    \Log::error('Product ID is missing in cart item', ['cartItem' => $cartItem]);
                    throw new \Exception('Product ID is missing in cart item');
                }

                if (!isset($cartItem['quantity'])) {
                    \Log::error('Quantity is missing in cart item', ['cartItem' => $cartItem]);
                    throw new \Exception('Quantity is missing in cart item');
                }

                if (!isset($cartItem['price'])) {
                    \Log::error('Price is missing in cart item', ['cartItem' => $cartItem]);
                    throw new \Exception('Price is missing in cart item');
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem['product_id'],
                    'quantity' => $cartItem['quantity'],
                    'price' => $cartItem['price'],
                ]);
            }


            session()->forget(['cart', 'cart_total']);
        });

        return redirect()->route('front.index')->with('success', 'Order placed successfully!');
    }


    public function myOrders()
    {
        $orders = Order::where('user_id', auth()->id())
            // ->orWhere('guest_id', session('guest_id'))
            ->with('orderItems.product')
            ->get();


        return view('front.index', compact('orders'));

    }

    public function showTrackOrderForm()
    {

        return view('orders.track');
    }

    public function trackOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::with('orderHistories')->findOrFail($request->order_id);


        return view('orders.track-details', compact('order'));
    }

    public function index($userId)
    {
        $user = User::findOrFail($userId);
        $orders = Order::where('user_id', $user->id)->with('orderItems.product')->get();

        return view('front.index', compact('orders'));
    }

}