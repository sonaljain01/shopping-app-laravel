@extends('front.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xl-12 text-center mb-5">
                <h2>Checkout</h2>
            </div>
        </div>

        <div class="row justify-content-between">
            <div class="col-12 col-lg-7 col-md-12">
                <h5 class="mb-4 ft-medium">Billing Details</h5>
                <form action="{{ route('checkout.placeOrder') }}" method="POST">
                    @csrf
                    <div class="row mb-2">
                        @foreach (['username', 'email', 'company', 'address_1', 'address_2', 'city', 'state', 'phone'] as $field)
                            <div class="col-{{ in_array($field, ['username']) ? '6' : '12' }}">
                                <div class="form-group">
                                    <label class="text-dark">{{ ucfirst(str_replace('_', ' ', $field)) }}
                                        {{ in_array($field, ['username', 'email', 'address_1', 'phone']) ? '*' : '' }}</label>
                                    <input type="{{ in_array($field, ['email']) ? 'email' : 'text' }}"
                                        name="{{ $field }}" class="form-control"
                                        placeholder="{{ ucfirst(str_replace('_', ' ', $field)) }}" required />
                                </div>
                            </div>
                        @endforeach
            
                        <!-- Zip Code Validation -->
                        <div class="col-12">
                            <div class="form-group">
                                <label for="zip" class="text-dark">Zip Code *</label>
                                <input type="text" name="zip" id="zip" class="form-control" placeholder="Enter your zip code" required />
                            </div>
                        </div>
            
                        <!-- Delivery Availability Message -->
                        <div id="delivery-message" class="col-12">
                            <p id="delivery-status"></p>
                        </div>
            
                        <!-- Country Selection -->
                        <div class="col-12">
                            <div class="form-group">
                                <label class="text-dark">Country *</label>
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
                                <label class="text-dark">Additional Information</label>
                                <textarea name="additional_information" class="form-control ht-50"></textarea>
                            </div>
                        </div>
                    </div>
            
                    <!-- Account Creation Toggle -->
                    <div class="row mb-4">
                        <div class="col-12 d-block">
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
            
            <!-- JavaScript -->
            <script>
                // Zip Code Validation and Delivery Availability Check
                document.getElementById('zip').addEventListener('blur', function () {
                    var zipCode = this.value.trim();
            
                    // Check if zip is not empty
                    if (zipCode) {
                        fetch(`https://api.postalpincode.in/pincode/${zipCode}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data[0].Status === 'Success') {
                                    const city = data[0].PostOffice[0].Division;
                                    const state = data[0].PostOffice[0].State;
            
                                    // Assuming you are dynamically filling city and state
                                    document.getElementById('city').value = city;
                                    document.getElementById('state').value = state;
            
                                    // Check if delivery is available for this city and state
                                    checkDeliveryAvailability(city, state);
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
                    // Perform an AJAX request or check against your database
                    // For simplicity, assume we're checking the city and state in the database
                    fetch(`/check-delivery/${city}/${state}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.delivery_available) {
                                displayMessage("Delivery is available to your location.");
                            } else {
                                displayMessage("Delivery is not available to this address.");
                            }
                        })
                        .catch(error => {
                            console.error('Error checking delivery availability:', error);
                            displayMessage("Error checking delivery availability.");
                        });
                }
            
                function displayMessage(message) {
                    document.getElementById('delivery-status').textContent = message;
                    document.getElementById('delivery-status').style.color = message.includes("not available") ? "red" : "green";
                }
            
                // Show/Hide Password Field based on 'Create An Account' checkbox
                function togglePasswordField() {
                    const passwordField = document.getElementById('passwordField');
                    passwordField.style.display = document.getElementById('createaccount').checked ? 'block' : 'none';
                }
            </script>
            

            <!-- Sidebar for Order Items -->
            <div class="col-12 col-lg-4 col-md-12">
                <div class="d-block mb-3">
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
                                            <h4 class="fs-md ft-medium mb-3 lh-1">
                                                Rs.{{ number_format($product->price, 2) }}</h4>
                                            <h4 class="fs-md ft-medium mb-3 lh-1">Qty:
                                                {{ $cartItems[$product->id]['quantity'] ?? 1 }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

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
@endsection
