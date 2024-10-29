@extends('front.layouts.app')

@section('content')
<section class="middle">
    <div class="container">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="text-center d-block mb-5">
                    <h2>Shopping Cart</h2>
                </div>
            </div>
        </div>

        <div class="row justify-content-between">
            <div class="col-12 col-lg-7 col-md-12">
                <ul class="list-group list-group-sm list-group-flush-y list-group-flush-x mb-4">
                    @if (empty(session('cart')))
                        <li class="list-group-item">Your cart is empty!</li>
                    @else
                        @foreach (session('cart') as $productId => $item)
                            <li class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-3">
                                        <!-- Image -->
                                        <a href="{{ route('product.show', $productId) }}">
                                            <img src="{{ asset($item['image']) }}" alt="{{ $item['title'] }}" class="img-fluid">
                                        </a>
                                    </div>
                                    <div class="col d-flex align-items-center justify-content-between">
                                        <div class="cart_single_caption pl-2">
                                            <h4 class="product_title fs-md ft-medium mb-1 lh-1">{{ $item['title'] }}</h4>
                                            <p class="mb-1 lh-1"><span class="text-dark">Size: {{ $item['size'] ?? 'N/A' }}</span></p>
                                            <h4 class="fs-md ft-medium mb-3 lh-1">Rs.{{ $item['price'] }}</h4>
                                            <select class="mb-2 custom-select w-auto">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <option value="{{ $i }}" {{ $i === $item['quantity'] ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="fls_last">
                                            <form action="{{ route('cart.remove', $productId) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="close_slide gray"><i class="ti-close"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>

                <div class="row align-items-end justify-content-between mb-10 mb-md-0">
                    <div class="col-12 col-md-7">
                        <!-- Coupon -->
                        <form class="mb-7 mb-md-0">
                            <label class="fs-sm ft-medium text-dark">Coupon code:</label>
                            <div class="row form-row">
                                <div class="col">
                                  <input class="form-control" type="text" placeholder="Enter coupon code*">
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-dark" type="submit">Apply</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 col-md-auto mfliud">
                        <button class="btn stretched-link borders">Update Cart</button>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-12 col-lg-4">
                <div class="card mb-4 gray mfliud">
                    <div class="card-body">
                        <ul class="list-group list-group-sm list-group-flush-y list-group-flush-x">
                            <li class="list-group-item d-flex text-dark fs-sm ft-regular">
                                <span>Subtotal</span>
                                <span class="ml-auto text-dark ft-medium">
                                    Rs.{{ array_reduce(session('cart'), fn($total, $item) => $total + ($item['price'] * $item['quantity']), 0) }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex text-dark fs-sm ft-regular">
                                <span>Tax</span>
                                <span class="ml-auto text-dark ft-medium">Rs.10.10</span>
                            </li>
                            <li class="list-group-item d-flex text-dark fs-sm ft-regular">
                                <span>Total</span>
                                <span class="ml-auto text-dark ft-medium">
                                    Rs.{{ array_reduce(session('cart'), fn($total, $item) => $total + ($item['price'] * $item['quantity']), 0) + 10.10 }} <!-- Adjust total with tax -->
                                </span>
                            </li>
                            <li class="list-group-item fs-sm text-center">
                                Shipping cost calculated at Checkout *
                            </li>
                        </ul>
                    </div>
                </div>

                <a class="btn btn-block btn-dark mb-3" href="{{ route('front.checkout') }}">Proceed to Checkout</a>
                <a class="btn-link text-dark ft-medium" href="shop.html">
                    <i class="ti-back-left mr-2"></i> Continue Shopping
                </a>
            </div>
        </div>
    </div>
    </div>
</section>
@endsection
