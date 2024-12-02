<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\State;
use Illuminate\Http\Request;
use App\Models\Product;
use DB;
use App\Models\User;
use Hash;
use Auth;
use Http;
use App\Models\City;
use App\Models\Menu;

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
        // $country = session('country', 'IN');
        // if (auth()->check()) {
        //     $country = auth()->user()->country ?? $country;
        // } elseif (!session('country')) {
        //     $ip = request()->ip() ?? '146.70.245.84';
        //     $data = getLocationInfo($ip);
        //     $country = $data['data']['country'] ?? $country;
        // }
        $country = 'IN';
        if (!session()->has('country')) {
            if (auth()->check()) {
                session()->put('country', auth()->user()->country);
            } else {
                $ip = request()->ip() ?? '146.70.245.84';
                $data = getLocationInfo($ip);
                $country = $data['data']['country'] ?? $country;
                session()->put('country', $country);
            }
        }

        $country = session('country', 'IN');
        $telcode = getTelCode($country)['code'];
        return view('front.checkout', compact('cartItems', 'products', 'subtotal', 'tax', 'total', 'paymentMethods', 'headerMenus', 'footerMenus', 'telcode'));

    }

    // public function placeOrder(Request $request)

    // {
    //     // Check and normalize the "same_as_billing" input
    //     $sameAsBilling = $request->has('same_as_billing') && $request->input('same_as_billing') === 'on';

    //     // Validate the request data
    //     $validatedData = $request->validate([
    //         'name' => 'required|string',
    //         'company' => 'nullable|string',
    //         'address_1' => 'required|string',
    //         'address_2' => 'nullable|string',
    //         'zip' => 'required|numeric',
    //         'city' => 'required|string',
    //         'state' => 'nullable|string',
    //         'phone' => 'required|numeric',
    //         'country' => 'nullable|string',
    //         'country_code' => 'required|string',
    //         'payment_method' => 'required|in:cod,razorpay',
    //         'additional_information' => 'nullable|string',
    //         'same_as_billing' => 'nullable|string',
    //         // Conditional validation for shipping address
    //         'shipping_name' => $sameAsBilling ? 'nullable' : 'required|string',
    //         'shipping_company' => 'nullable|string',
    //         'shipping_address_1' => $sameAsBilling ? 'nullable' : 'required|string',
    //         'shipping_address_2' => 'nullable|string',
    //         'shipping_zip' => $sameAsBilling ? 'nullable' : 'required|numeric',
    //         'shipping_city' => $sameAsBilling ? 'nullable' : 'required|string',
    //         'shipping_state' => 'nullable|string',
    //         'shipping_phone' => $sameAsBilling ? 'nullable' : 'required|string',
    //         'shipping_country_code' => $sameAsBilling ? 'nullable' : 'required|string',
    //         'shipping_country' => 'nullable|string',
    //     ]);

    //     // Handle location validation based on the zip code
    //     $locationDetails = $this->getLocationFromPincode($validatedData['zip']);
    //     if (!$locationDetails || !$locationDetails['city_enabled']) {
    //         return redirect()->route('front.checkout')->withErrors(['delivery_error' => 'Delivery is not available to this location.']);
    //     }

    //     // Update city and state from location details
    //     $validatedData['city'] = $locationDetails['city'];
    //     $validatedData['state'] = $locationDetails['state'];

    //     // Use database transaction to handle the order creation
    //     DB::transaction(function () use ($validatedData, $sameAsBilling, $request) {
    //         $cartItems = session('cart', []);
    //         $totalAmount = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);

    //         if ($totalAmount <= 0) {
    //             throw new \Exception('Cart total is invalid.');
    //         }

    //         $order = Order::create([
    //             'user_id' => auth()->id(),
    //             'guest_id' => session('guest_id'),
    //             'payment_method' => $validatedData['payment_method'],
    //             'status' => 'In Progress',
    //             'total_amount' => $totalAmount,
    //             // 'phone' => $validatedData['phone'],
    //             'phone' => $validatedData['country_code'] . $validatedData['phone'],
    //             'billing_address_id' => null,
    //             'shipping_address_id' => null,
    //             'country_code' => $request->country_code,

    //         ]);

    //         // Create billing address
    //         $billingAddress = Address::create([
    //             'user_id' => auth()->id(),
    //             'name' => $validatedData['name'],
    //             'company' => $validatedData['company'],
    //             'address_1' => $validatedData['address_1'],
    //             'address_2' => $validatedData['address_2'],
    //             'city' => $validatedData['city'],
    //             'state' => $validatedData['state'],
    //             'zip' => $validatedData['zip'],
    //             'phone' => $validatedData['phone'],
    //             'country' => $validatedData['country'],
    //             'country_code' => $request->country_code,
    //             'additional_information' => $validatedData['additional_information'],
    //             'type' => 'billing',
    //             'is_default' => true,
    //         ]);

    //         // Check if shipping address should be the same as billing address
    //         if ($sameAsBilling) {
    //             $shippingAddress = $billingAddress->replicate()->fill(['type' => 'shipping', 'is_default' => false]);
    //             $shippingAddress->save();
    //         } else {
    //             $shippingAddress = Address::create([
    //                 'user_id' => auth()->id(),
    //                 'name' => $validatedData['shipping_name'],
    //                 'company' => $validatedData['shipping_company'],
    //                 'address_1' => $validatedData['shipping_address_1'],
    //                 'address_2' => $validatedData['shipping_address_2'],
    //                 'city' => $validatedData['shipping_city'],
    //                 'state' => $validatedData['shipping_state'],
    //                 'zip' => $validatedData['shipping_zip'],
    //                 'phone' => $validatedData['shipping_phone'],
    //                 'country' => $validatedData['shipping_country'],
    //                 'country_code' => $request->country_code,
    //                 'additional_information' => $validatedData['additional_information'],
    //                 'type' => 'shipping',
    //                 'is_default' => false,
    //             ]);
    //         }

    //         // Assign addresses to the order
    //         $order->billing_address_id = $billingAddress->id;
    //         $order->shipping_address_id = $shippingAddress->id;
    //         $order->save();

    //         // Add order items
    //         foreach ($cartItems as $cartItem) {
    //             $product = Product::find($cartItem['product_id']);
    //             if (!$product) {
    //                 throw new \Exception('Product not found with ID: ' . $cartItem['product_id']);
    //             }

    //             OrderItem::create([
    //                 'order_id' => $order->id,
    //                 'product_id' => $cartItem['product_id'],
    //                 'product_name' => $product->title,
    //                 'product_price' => $product->price,
    //                 'quantity' => $cartItem['quantity'],
    //                 'price' => $cartItem['price'],
    //                 'subtotal' => $cartItem['price'] * $cartItem['quantity'],
    //             ]);
    //         }

    //         // Clear the session cart
    //         session()->forget(['cart', 'cart_total']);
    //     });

    //     return redirect()->route('front.index')->with('success', 'Order placed successfully!');
    // }

    public function placeOrder(Request $request)
    {
        $sameAsBilling = $request->boolean('same_as_billing');

        // Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string',
            'company' => 'nullable|string',
            'address_1' => 'required|string',
            'address_2' => 'nullable|string',
            'zip' => 'required|numeric',
            'city' => 'required|string',
            'state' => 'nullable|string',
            'phone' => 'required|numeric',
            'country' => 'nullable|string',
            'country_code' => 'required|string',
            'payment_method' => 'required|in:cod,razorpay',
            'additional_information' => 'nullable|string',
            'shipping_name' => $sameAsBilling ? 'nullable' : 'required|string',
            'shipping_company' => 'nullable|string',
            'shipping_address_1' => $sameAsBilling ? 'nullable' : 'required|string',
            'shipping_address_2' => 'nullable|string',
            'shipping_zip' => $sameAsBilling ? 'nullable' : 'required|numeric',
            'shipping_city' => $sameAsBilling ? 'nullable' : 'required|string',
            'shipping_state' => 'nullable|string',
            'shipping_phone' => $sameAsBilling ? 'nullable' : 'required|string',
            'shipping_country_code' => $sameAsBilling ? 'nullable' : 'required|string',
            'shipping_country' => 'nullable|string',
        ]);

        // Validate location details
        $locationDetails = $this->getLocationFromPincode($validatedData['zip']);
        if (!$locationDetails || !$locationDetails['city_enabled']) {
            return redirect()->route('front.checkout')->withErrors(['delivery_error' => 'Delivery is not available to this location.']);
        }

        // Update city and state from location details
        $validatedData['city'] = $locationDetails['city'];
        $validatedData['state'] = $locationDetails['state'];

        DB::transaction(function () use ($validatedData, $sameAsBilling) {
            $cartItems = session('cart', []);
            $totalAmount = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);

            if ($totalAmount <= 0) {
                throw new \Exception('Cart total is invalid.');
            }

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'guest_id' => session('guest_id'),
                'payment_method' => $validatedData['payment_method'],
                'status' => 'In Progress',
                'total_amount' => $totalAmount,
                'phone' => $validatedData['country_code'] . $validatedData['phone'],
            ]);

            // Create billing address
            $billingAddress = Address::create($this->prepareAddressData($validatedData, 'billing'));

            // Create shipping address
            $shippingAddress = $sameAsBilling
                ? $billingAddress->replicate(['type' => 'shipping', 'is_default' => false])->save()
                : Address::create($this->prepareAddressData($validatedData, 'shipping', true));

            // Update order with address IDs
            $order->update([
                'billing_address_id' => $billingAddress->id,
                'shipping_address_id' => $shippingAddress->id,
            ]);

            // Add order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem['product_id'],
                    'product_name' => $cartItem['name'],
                    'product_price' => $cartItem['price'],
                    'quantity' => $cartItem['quantity'],
                    'subtotal' => $cartItem['price'] * $cartItem['quantity'],
                ]);
            }

            // Clear the session cart
            session()->forget(['cart', 'cart_total']);
        });

        return redirect()->route('front.index')->with('success', 'Order placed successfully!');
    }

    private function prepareAddressData(array $data, string $type, bool $isShipping = false): array
    {
        return [
            'user_id' => auth()->id(),
            'name' => $isShipping ? $data['shipping_name'] : $data['name'],
            'company' => $isShipping ? $data['shipping_company'] : $data['company'],
            'address_1' => $isShipping ? $data['shipping_address_1'] : $data['address_1'],
            'address_2' => $isShipping ? $data['shipping_address_2'] : $data['address_2'],
            'city' => $isShipping ? $data['shipping_city'] : $data['city'],
            'state' => $isShipping ? $data['shipping_state'] : $data['state'],
            'zip' => $isShipping ? $data['shipping_zip'] : $data['zip'],
            'phone' => $isShipping ? $data['shipping_phone'] : $data['phone'],
            'country' => $isShipping ? $data['shipping_country'] : $data['country'],
            'country_code' => $isShipping ? $data['shipping_country_code'] : $data['country_code'],
            'additional_information' => $data['additional_information'] ?? null,
            'type' => $type,
            'is_default' => $type === 'billing',
        ];
    }


    private function validateCountryDialCode($countryName, $dialCode)
    {
        $response = Http::get("https://restcountries.com/v3.1/name/{$countryName}");
        if ($response->ok()) {
            $data = $response->json();
            if (!empty($data[0]['idd']) && in_array(str_replace('+', '', $dialCode), $data[0]['idd']['suffixes'])) {
                return true;
            }
        }
        return false;
    }

    private function getLocationFromPincode($pincode)
    {
        try {
            // Example API endpoint (replace with a real one)
            // $apiUrl = "https://api.example.com/pincode/{$pincode}";
            $apiUrl = "https://api.postalpincode.in/pincode/{$pincode}";

            // Make an API request
            $response = Http::get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();

                // Check for valid API response and get location details
                if (isset($data[0]['Status']) && $data[0]['Status'] === 'Success') {
                    $postOffice = $data[0]['PostOffice'][0];

                    // Fetch city and state and check if the city is enabled
                    $city = $postOffice['District'];
                    $state = $postOffice['State'];

                    // Custom logic to determine if delivery is enabled
                    $cityEnabled = City::where('name', $city)
                        ->where('state_id', function ($query) use ($state) {
                            $query->select('id')->from('states')->where('name', $state);
                        })
                        ->where('is_enabled', 1)
                        ->exists();

                    return [
                        'city' => $city,
                        'state' => $state,
                        'city_enabled' => $cityEnabled
                    ];
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to fetch location for pincode: ' . $pincode, ['error' => $e->getMessage()]);
        }

        return null; // Return null if API call fails or if city is not enabled
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'company' => 'nullable|string',
            'address_1' => 'required|string',
            'address_2' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'nullable|string',
            'zip' => 'required|numeric',
            'phone' => 'required|string',
            'country' => 'nullable|string',
            'payment_method' => 'required|in:cod,razorpay',
            'additional_information' => 'nullable|string',
        ]);

        // Save the address logic
        $address = Address::create($validated);

        return response()->json(['success' => true, 'message' => 'Billing address saved successfully!']);
    }

    public function myOrders()
    {
        $orders = Order::where('user_id', auth()->id())
            // ->orWhere('guest_id', session('guest_id'))
            ->with('orderItems.product')
            ->get();

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
        return view('front.index', compact('orders', 'headerMenus', 'footerMenus'));

    }

    public function showTrackOrderForm()
    {
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
        return view('orders.track', [
            'headerMenus' => $headerMenus,
            'footerMenus' => $footerMenus
        ]);
    }

    public function trackOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::with('orderHistories')->findOrFail($request->order_id);
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
        return view('orders.track-details', compact('order', 'headerMenus', 'footerMenus'));
    }

    public function index($userId)
    {
        $user = User::findOrFail($userId);
        $orders = Order::where('user_id', $user->id)->with('orderItems.product')->get();

        return view('front.index', compact('orders'));
    }

    public function checkDelivery($cityName, $stateName)
    {
        $state = State::where('name', $stateName)->where('is_enabled', 1)->first();

        if (!$state) {
            // If the state is not enabled, return delivery not available
            return ['delivery_available' => false, 'message' => 'Delivery not available. State is disabled.'];
        }

        // If the state is enabled, now check the city
        $city = City::where('name', $cityName)
            ->where('is_enabled', 1) // Ensure the city is enabled
            ->where('state_id', $state->id) // Ensure the city belongs to the correct enabled state
            ->first();

        // Check if both state and city are enabled
        if ($city) {
            return ['delivery_available' => true, 'message' => 'Delivery is available.'];
        } else {
            return ['delivery_available' => false, 'message' => 'Delivery not available. City is disabled or does not exist.'];
        }


    }
}