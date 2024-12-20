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
                        
                        <div class="form-group col-md-6">
                            <label for="country_code">Country Code *</label>
                            <input type="text" class="form-control" name="country_code" id="country_code" 
                                value="{{ $telcode }}" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" class="form-control" name="phone" id="phone" required>
                        </div>
                        
                        
                        <!-- Country Selection -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="country">Country <span class="text-danger">*</span></label>
                                <input type="text" id="country" name="country" class="form-control" placeholder="Enter Country" onblur="fetchDialCode()" required>
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
                                        <img src="{{ asset('public/uploads/product/' . $product->image) }}"
                                            alt="{{ $product->name }}" class="img-fluid">
                                    </a>
                                </div>
                                <div class="col d-flex align-items-center">
                                    <div class="cart_single_caption pl-2">
                                        <h4 class="product_title fs-md ft-medium mb-1 lh-1">{{ $product->title }}</h4>
                                        <h4 class="fs-md ft-medium mb-3 lh-1">{{ $currency }} {{ number_format($product->original, 2) }}
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
                            <!-- Subtotal -->
                            <li class="list-group-item d-flex text-dark fs-sm ft-regular">
                                <span>Subtotal</span>
                                <span class="ml-auto text-dark ft-medium">
                                    @if ($product->tax_type === 'inclusive')
                                        <!-- If tax is inclusive, display subtotal without tax -->
                                        {{ $currency }} {{ number_format($subtotal, 2) }}
                                    @else
                                        <!-- For exclusive and no_tax, display full subtotal -->
                                        {{ $currency }} {{ number_format($subtotal, 2) }}
                                    @endif
                                </span>
                            </li>
                    
                            <!-- Tax -->
                            <li class="list-group-item d-flex text-dark fs-sm ft-regular">
                                <span>Tax</span>
                                <span class="ml-auto text-dark ft-medium">
                                    @if ($product->tax_type === 'no_tax')
                                        <!-- If no_tax, tax amount is 0 -->
                                        {{ $currency }} 0.00
                                    @elseif ($products->firstWhere('tax_type', 'exclusive'))
                                        <!-- For exclusive tax, display calculated tax -->
                                        {{ $currency }} {{ number_format($totalTax, 2) }}
                                    @else
                                        <!-- For inclusive and exclusive, display calculated tax -->
                                        {{ $currency }} {{ number_format($totalTax, 2) }}
                                    @endif
                                </span>
                            </li>
                    
                            <!-- Total -->
                            <li class="list-group-item d-flex text-dark fs-sm ft-regular">
                                <span>Total</span>
                                <span class="ml-auto text-dark ft-medium">
                                    <!-- Total is always subtotal + tax -->
                                    {{ $currency }} {{ number_format($total, 2) }}
                                </span>
                            </li>
                    
                            <!-- Shipping Note -->
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