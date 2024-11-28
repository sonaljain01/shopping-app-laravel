@extends('front.layouts.app')

@section('content')
    <div class="gray py-3">
        <div class="container">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Support</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xl-12 text-center mb-5">
                <h2>Checkout</h2>
            </div>

            @if ($errors->has('delivery_error'))
                <div class="alert alert-danger">
                    {{ $errors->first('delivery_error') }}
                </div>
            @endif
        </div>

        <div class="row justify-content-between">
            <div class="col-12 col-lg-7 col-md-12">
                <h5 class="mb-4 ft-medium">Billing Details</h5>
                <form action="{{ route('checkout.placeOrder') }}" method="POST">
                    @csrf
                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="name"
                                    required />
                            </div>
                        </div>

                        <!-- Company Field -->
                        <div class="col-6">
                            <div class="form-group">
                                <label for="company">Company</label>
                                <input type="text" id="company" name="company" class="form-control"
                                    placeholder="Company" />
                            </div>
                        </div>

                        <!-- Address 1 Field -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="address_1">Address Line 1 <span class="text-danger">*</span></label>
                                <input type="text" id="address_1" name="address_1" class="form-control"
                                    placeholder="Address Line 1" required />
                            </div>
                        </div>

                        <!-- Address 2 Field -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="address_2">Address Line 2</label>
                                <input type="text" id="address_2" name="address_2" class="form-control"
                                    placeholder="Address Line 2" />
                            </div>
                        </div>

                        <!-- Zip Code Validation -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="zip">Zip Code *</label>
                                <input type="text" name="zip" id="zip" class="form-control"
                                    placeholder="Enter your zip code" required />
                            </div>
                        </div>

                        <!-- Delivery Availability Message -->
                        <div id="delivery-message" class="col-12">
                            <p id="delivery-status"></p>

                        </div>

                        <!-- City Field -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="city">City <span class="text-danger">*</span></label>
                                <input type="text" id="city" name="city" class="form-control" placeholder="City"
                                    readonly required />
                            </div>
                        </div>

                        <!-- State Field -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="state">State <span class="text-danger">*</span></label>
                                <input type="text" id="state" name="state" class="form-control" placeholder="State"
                                    readonly required />
                            </div>
                        </div>

                        <!-- Phone Field -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="phone">Phone <span class="text-danger">*</span></label>
                                <input type="text" id="phone" name="phone" class="form-control" placeholder="Phone"
                                    required />
                            </div>
                        </div>

                        <!-- Country Selection -->
                        <div class="col-12">
                            <div class="form-group">
                                <label>Country *</label>
                                <select name="country" class="custom-select" required>
                                    <option value="India" selected="">India</option>
                                    <option value="United States">United States</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="China">China</option>
                                    <option value="Pakistan">Pakistan</option>
                                </select>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="col-12">
                            <div class="form-group">
                                <label>Additional Information</label>
                                <textarea name="additional_information" class="form-control ht-50"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Account Creation Toggle -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <input id="createaccount" class="checkbox-custom" name="createaccount" type="checkbox"
                                onclick="togglePasswordField()">
                            <label for="createaccount" class="checkbox-custom-label">Create An Account?</label>
                        </div>
                    </div>

                    <!-- Password Field, initially hidden -->
                    <div class="form-group" id="passwordField" style="display: none;">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" minlength="8"
                            placeholder="Enter password for account creation">
                    </div>
                    {{-- @include('partials.billing-address') --}}

                    <!-- Same as Billing Address Toggle -->
                    <div class="col-12 mb-4">
                        <input type="checkbox" id="same_as_billing" name="same_as_billing" class="checkbox-custom"
                            onclick="toggleShippingFields()">
                        {{-- <label for="same_as_billing" class="checkbox-custom-label">Shipping address same as
                            billing?</label> --}}@include('partials.shipping-address')
                    </div>

                    <!-- Shipping Address Section -->
                    <div id="shipping-address-section" style="display: none;">
                       
                        @include('partials.shipping-address')
                    </div>


                    <script>
                        function toggleShippingFields() {
                            const isChecked = document.getElementById('same_as_billing').checked;
                            const shippingSection = document.getElementById('shipping-address-section');
                            if (isChecked) {
                                shippingSection.style.display = 'none';
                            } else {
                                shippingSection.style.display = 'block';
                            }
                        }
                    </script>



                    <h5 class="mb-4 ft-medium">Payments</h5>
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="panel-group pay_opy980" id="payaccordion">
                                <select name="payment_method" required>
                                    <option value="cod">Cash on Delivery</option>
                                    @if ($paymentMethods->contains('name', 'razorpay'))
                                        <option value="razorpay">Razorpay</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-block btn-dark mb-3">Place Your Order</button>
                    </div>
                </form>
            </div>

            <div class="col-12 col-lg-4 col-md-12">
                <h5 class="mb-4">Order Items</h5>
                <ul class="list-group list-group-sm list-group-flush-y list-group-flush-x mb-4">
                    @foreach ($products as $product)
                        <li class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <a href="{{ route('product.show', $product->id) }}">
                                        <img src="{{ asset('public/uploads/products/' . $product->image) }}"
                                            alt="{{ $product->name }}" class="img-fluid">
                                    </a>
                                </div>
                                <div class="col d-flex align-items-center">
                                    <div class="cart_single_caption pl-2">
                                        <h4 class="product_title fs-md ft-medium mb-1 lh-1">{{ $product->title }}</h4>
                                        <h4 class="fs-md ft-medium mb-3 lh-1">Rs.{{ number_format($product->price, 2) }}
                                        </h4>
                                        <h4 class="fs-md ft-medium mb-3 lh-1">Qty:
                                            {{ $cartItems[$product->id]['quantity'] ?? 1 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="card mb-4 gray">
                    <div class="card-body">
                        <ul class="list-group list-group-sm list-group-flush-y list-group-flush-x">
                            <li class="list-group-item d-flex text-dark fs-sm ft-regular">
                                <span>Subtotal</span>
                                <span class="ml-auto text-dark ft-medium">Rs.{{ number_format($subtotal, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex text-dark fs-sm ft-regular">
                                <span>Tax</span>
                                <span class="ml-auto text-dark ft-medium">Rs.{{ number_format($tax, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex text-dark fs-sm ft-regular">
                                <span>Total</span>
                                <span class="ml-auto text-dark ft-medium">Rs.{{ number_format($total, 2) }}</span>
                            </li>
                            <li class="list-group-item fs-sm text-center">
                                Shipping cost calculated at Checkout *
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const zipInput = document.getElementById('zip');
            const deliveryStatus = document.getElementById('delivery-status');

            if (!deliveryStatus) {
                console.error("Delivery status element not found!");
                return;
            }

            zipInput.addEventListener('change', function() {
                const zip = this.value.trim();

                if (zip) {
                    fetch(`https://api.postalpincode.in/pincode/${zip}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data[0].Status === 'Success') {
                                const city = data[0].PostOffice[0].Division;
                                const state = data[0].PostOffice[0].State;

                                const cityInput = document.getElementById('city');
                                const stateInput = document.getElementById('state');

                                if (cityInput && stateInput) {
                                    cityInput.value = city;
                                    stateInput.value = state;

                                    checkDeliveryAvailability(city, state);
                                } else {
                                    console.error('City or State input field not found.');
                                }
                            } else {
                                displayMessage("Invalid pincode or no delivery available.");
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching zip code data:', error);
                            displayMessage("Error checking delivery availability.");
                        });
                } else {
                    displayMessage("Please enter a valid zip code.");
                }
            });

            function checkDeliveryAvailability(city, state) {
                const routeTemplate =
                    `{{ route('check-delivery', ['city' => '__CITY__', 'state' => '__STATE__']) }}`;
                const finalUrl = routeTemplate
                    .replace('__CITY__', encodeURIComponent(city))
                    .replace('__STATE__', encodeURIComponent(state));

                fetch(finalUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (data.delivery_available) {
                            displayMessage("Delivery is available to your location.");
                        } else {
                            displayMessage("Sorry, we do not deliver to your location.");
                        }
                    })
                    .catch(error => {
                        console.error("Error checking delivery availability:", error);
                        displayMessage("Error checking delivery availability.");
                    });
            }

            function displayMessage(message) {
                deliveryStatus.textContent = message;
            }
        });

        function togglePasswordField() {
            const passwordField = document.getElementById("passwordField");
            passwordField.style.display = passwordField.style.display === "none" ? "block" : "none";
        }
    </script>
@endsection


















public function placeOrder(Request $request)
    {
        dd($request->all());
        $validatedData = $request->validate([
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
            'shipping_address_same_as_billing' => 'required|boolean',
            // Conditional validation for shipping address
            'shipping_name' => 'required_if:shipping_address_same_as_billing,false|string',
            'shipping_address_1' => 'required_if:shipping_address_same_as_billing,false|string',
            'shipping_city' => 'required_if:shipping_address_same_as_billing,false|string',
            'shipping_state' => 'nullable|string',
            'shipping_zip' => 'required_if:shipping_address_same_as_billing,false|numeric',
            'shipping_phone' => 'required_if:shipping_address_same_as_billing,false|string',
            'shipping_country' => 'nullable|string',
        ]);
        $sameAsBilling = $request->input('shipping_address_same_as_billing');

      
        // Fetch location details based on zip code
        $locationDetails = $this->getLocationFromPincode($validatedData['zip']);
        if (!$locationDetails || !$locationDetails['city_enabled']) {
            return redirect()->route('front.checkout')->withErrors(['delivery_error' => 'Delivery is not available to this location.']);
        }

        // Use city and state from location details
        $validatedData['city'] = $locationDetails['city'];
        $validatedData['state'] = $locationDetails['state'];

        try {
            DB::transaction(function () use ($validatedData, $sameAsBilling) {
                // Calculate the cart total
                $cartItems = session('cart', []);
                $totalAmount = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);
    
                if ($totalAmount <= 0) {
                    throw new \Exception('Cart total is invalid.');
                }
    
                // Create the order
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'guest_id' => session('guest_id'),
                    'payment_method' => $validatedData['payment_method'],
                    'status' => 'In Progress',
                    'total_amount' => $totalAmount,
                    'phone' => $validatedData['phone'],
                ]);
    
                if (!$order) {
                    throw new \Exception('Failed to create the order.');
                }
    
                // Create billing address
                $billingAddress = Address::create([
                    'user_id' => auth()->id(),
                    'name' => $validatedData['name'],
                    'company' => $validatedData['company'],
                    'address_1' => $validatedData['address_1'],
                    'address_2' => $validatedData['address_2'],
                    'city' => $validatedData['city'],
                    'state' => $validatedData['state'],
                    'zip' => $validatedData['zip'],
                    'phone' => $validatedData['phone'],
                    'country' => $validatedData['country'],
                    'additional_information' => $validatedData['additional_information'],
                    'type' => 'billing',
                    'is_default' => true,
                ]);
    
                if (!$billingAddress) {
                    throw new \Exception('Failed to create billing address.');
                }
    
                // Create shipping address
                if ($sameAsBilling) {
                    $shippingAddress = $billingAddress->replicate()->fill([
                        'type' => 'shipping',
                        'is_default' => false,
                    ]);
                    $shippingAddress->save();
                } else {
                    $shippingAddress = Address::create([
                        'user_id' => auth()->id(),
                        'name' => $validatedData['shipping_name'],
                        'company' => $validatedData['company'],
                        'address_1' => $validatedData['shipping_address_1'],
                        'address_2' => $validatedData['shipping_address_2'],
                        'city' => $validatedData['shipping_city'],
                        'state' => $validatedData['shipping_state'],
                        'zip' => $validatedData['shipping_zip'],
                        'phone' => $validatedData['shipping_phone'],
                        'country' => $validatedData['shipping_country'],
                        'additional_information' => $validatedData['additional_information'],
                        'type' => 'shipping',
                        'is_default' => false,
                    ]);
    
                    if (!$shippingAddress) {
                        throw new \Exception('Failed to create shipping address.');
                    }
                }
    
                // Save order items
                foreach ($cartItems as $cartItem) {
                    $product = Product::find($cartItem['product_id']);
                    if (!$product) {
                        throw new \Exception('Product not found with ID: ' . $cartItem['product_id']);
                    }
    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem['product_id'],
                        'product_name' => $product->title,
                        'product_price' => $product->price,
                        'quantity' => $cartItem['quantity'],
                        'price' => $cartItem['price'],
                        'subtotal' => $cartItem['price'] * $cartItem['quantity'],
                    ]);
                }
    
                // Clear the cart
                session()->forget(['cart', 'cart_total']);
            });
    
            return redirect()->route('front.index')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return redirect()->route('front.checkout')
                ->withErrors(['error' => 'Order could not be placed: ' . $e->getMessage()]);
        }
    }














    <?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Http;
use App\Models\Shipment;
use App\Models\PickupAddress;

class ShipRocketController extends Controller
{
    protected $id;

    protected $token;

    public function __construct()
    {
        $this->token = env('SHIPROCKET_TOKEN');
    }

    public function createOrder($order)
    {
        try {
            $products = [];
            foreach ($order->products as $product) {
                $products[] = [
                    'name' => $product->product->title,
                    'sku' => $product->product->sku,
                    'units' => $product->quantity,
                    'selling_price' => $product->price,
                    'discount' => '',
                    'tax' => '',
                    'hsn' => 441122,
                ];
            }

            // Make the API request to ShipRocket
            $api = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
            ])->post('https://apiv2.shiprocket.in/v1/external/orders/create/adhoc', [
                        'order_id' => $order->id,
                        'order_date' => $order->created_at->format('Y-m-d H:i:s'),
                        'pickup_location' => 'Home',
                        'channel_id' => '5780891',
                        'comment' => '',
                        'billing_customer_name' => $order->user->name,
                        'billing_last_name' => '',
                        'billing_address' => $order->address->address_1,
                        'billing_address_2' => '',
                        'billing_city' => $order->address->city,
                        'billing_pincode' => $order->address->zip,
                        'billing_state' => $order->address->state,
                        'billing_country' => $order->address->country,
                        'billing_email' => $order->user->email ?? '',
                        'billing_phone' => $order->user->phone ?? '',
                        'shipping_is_billing' => true,
                        'order_items' => $products,
                        'payment_method' => $order->payment_method == 'cod' ? 'postpaid' : 'prepaid',
                        'shipping_charges' => 0,
                        'sub_total' => $order->total,
                        'length' => 10,
                        'breadth' => 15,
                        'height' => 20,
                        'weight' => 2.5,
                    ]);

            // Check if the request was successful
            if ($api->successful()) {
                return $api->json(); // Return the successful response as an array
            } else {
                // Log the error response for debugging
                Log::error('ShipRocket API Error: ' . $api->body());
                return [
                    'success' => false,
                    'message' => 'Failed to create order in ShipRocket. Error: ' . $api->body(),
                ];
            }
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Exception while creating ShipRocket order: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An exception occurred while processing the order: ' . $e->getMessage(),
            ];
        }
    }


    public function storeShipment($order)
    {
        $shiprocket = Shipment::updateOrCreate([
            'order_id' => $order['order_id'],
            'channel_order_id' => $order['channel_order_id'],
            'shipment_id' => $order['shipment_id'],
            'courier_name' => '',
            'status' => $order['status'],
            'pickup_address_id' => 2,
            'actual_weight' => '',
            'volumetric_weight' => '',
            'platform' => '5777349',
            'charges' => '',
        ]);

        if ($shiprocket) {
            return [
                'status' => true,
                'message' => 'Shipment created successfully',
            ];
        } else {
            return [
                'status' => false,
                'message' => 'Shipment not created',
            ];
        }
    }


}












<div class="currency-selector dropdown js-dropdown float-right">
                            <a href="javascript:void(0);" data-toggle="dropdown" class="popup-title" title="Currency"
                                aria-label="Currency dropdown">
                                <span class="hidden-xl-down medium text-light">Country:</span>
                                <span class="iso_code medium text-light"> <img
                                        src="{{ asset('vendor/blade-flags/country-') }}" width="20"
                                        height="20" />
                                </span>
                                <i class="fa fa-angle-down medium text-light"></i>
                            </a>
                            <ul class="popup-content dropdown-menu">
                                <li><a title="Euro" onclick="country(this.id)" href="javascript:void(0);"
                                        id="in" class="dropdown-item medium text-medium"><img
                                            src="{{ asset('vendor/bla   de-flags/country-in.svg') }}" width="32"
                                            height="32" />India</a>
                                </li>
                                <li><a title="Euro" onclick="country(this.id)" href="javascript:void(0);"
                                        id="eu" class="dropdown-item medium text-medium"><img
                                            src="{{ asset('vendor/blade-flags/country-eu.svg') }}" width="32"
                                            height="32" />Europe</a>
                                </li>
                                <<li class="current"><a title="US Dollar" href="javascript:void(0);" id="uk"
                                        onclick="country(this.id)" class="dropdown-item medium text-medium">
                                        <img src="{{ asset('vendor/blade-flags/country-uk.svg') }}" width="32"
                                            height="32" />
                                        UK</a></li>
                                    <li class="current"><a title="US Dollar" href="javascript:void(0);" id="us"
                                            onclick="country(this.id)" class="dropdown-item medium text-medium">
                                            <img src="{{ asset('vendor/blade-flags/country-us.svg') }}" width="32"
                                                height="32" />
                                            USA
                                        </a></li>
                                    <li class="current"><a title="US Dollar" href="javascript:void(0);" id="cn"
                                            onclick="country(this.id)" class="dropdown-item medium text-medium">
                                            <img src="{{ asset('vendor/blade-flags/country-cn.svg') }}" width="32"
                                                height="32" />
                                            China
                                        </a></li>
                            </ul>
                        </div>




















                        <section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <div class="card">
            <form action="" method="get">
                <div class="card-header">
                    <div class="card-title">
                        <button type="button" onclick="window.location.href='{{ route('pickup.index') }}'"
                            class="btn btn-default btn-sm">Reset</button>
                    </div>
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 250px;">
                            <input value="{{ Request::get('keyword') }}" type="text" name="keyword"
                                class="form-control float-right" placeholder="Search">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Pincode</th>
                            <th>Phone</th>
                            <th>tag</th>
                            <th>is_default</th>
                            <th width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pickupAddresses as $address)
                            <tr>
                                <td>{{ $address->id }}</td>
                                <td>{{ $address->name }}</td>
                                <td>{{ $address->address }}</td>
                                <td>{{ $address->city }}</td>
                                <td>{{ $address->state }}</td>
                                <td>{{ $address->pincode }}</td>
                                <td>{{ $address->phone }}</td>
                                <td>{{ $address->tag }}</td>
                                <td>{{ $address->is_default }}</td>
                                <td>
                                    <a href="{{ route('pickup.edit', $pickup->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    {{-- <form action="{{ route('pickup.destroy', $pickup->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Do you want to delete this address?')">Delete</button>
                                    </form> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $pickupAddresses->links() }}
            </div>
        </div>
    </div>
</section>















        // // Prevent updating if the order is already completed, cancelled, or shipped
        // if (in_array($order->status, ['cancelled', 'completed', 'shipped'])) {
        //     return response()->json(['success' => false, 'message' => 'Cannot update the status of an order that is ' . $order->status . '.'], 400);
        // }

        // // Handle "shipped" status with ShipRocket integration
        // if ($request->status === 'shipped') {
        //     // Validate that pickup address is provided
        //     $pickupAddress = PickupAddress::find($request->pickup);
        //     if (!$pickupAddress) {
        //         return response()->json(['success' => false, 'message' => 'Invalid pickup address.'], 400);
        //     }

        //     // Call ShipRocket to create the shipment order
        //     $shipRocketResponse = $shipRocketController->createOrder($order, $pickupAddress);

        //     // Verify ShipRocket response
        //     if (!$shipRocketResponse || !isset($shipRocketResponse['status'])) {
        //         return response()->json(['success' => false, 'message' => 'Failed to create order in ShipRocket.'], 500);
        //     }

        //     if ($shipRocketResponse['status'] === 200) {
        //         $shipRocketOrderData = $shipRocketResponse['data'] ?? [];

        //         // Update the order with ShipRocket details
        //         $order->update([
        //             'shiprocket_order_id' => $shipRocketOrderData['order_id'] ?? null,
        //             'shiprocket_tracking_number' => $shipRocketOrderData['tracking_number'] ?? null,
        //             'shiprocket_label_url' => $shipRocketOrderData['label_url'] ?? null,
        //             'status' => 'shipped',
        //         ]);

        //         // Store additional shipment details if needed
        //         $storeResponse = $shipRocketController->storeShipment($shipRocketOrderData);
        //         if (!$storeResponse['status']) {
        //             return response()->json(['success' => false, 'message' => $storeResponse['message']], 500);
        //         }
        //     } else {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Error processing order in ShipRocket: ' . ($shipRocketResponse['message'] ?? 'Unknown error.'),
        //         ], 500);
        //     }
        // } else {
        //     // Update order status for other statuses
        //     $order->status = $request->status;
        //     $order->save();
        // }

        // // Log the order status change
        // OrderHistory::create([
        //     'order_id' => $order->id,
        //     'status' => $order->status,
        //     'changed_at' => now(),
        // ]);

        // return response()->json(['success' => true, 'message' => 'Order status updated successfully']);