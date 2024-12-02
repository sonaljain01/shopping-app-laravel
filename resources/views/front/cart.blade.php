@extends('front.layouts.app')

@section('content')
<div class="gray py-3">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Support</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<section class="middle">
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
                <form id="cartForm" action="{{ route('cart.update') }}" method="POST">
                    @csrf
                    
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
                                                <h4 class="fs-md ft-medium mb-3 lh-1">  {{ number_format($item['price'], 2) }}</h4>
                                                <!-- Quantity Select -->
                                                <select name="quantities[{{ $productId }}]" class="mb-2 custom-select w-auto">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <option value="{{ $i }}" {{ $i == $item['quantity'] ? 'selected' : '' }}>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="fls_last">
                                                <button type="button" onclick="removeItem('{{ $productId }}')" class="close_slide gray">
                                                    <i class="ti-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                
                    <div class="row align-items-end justify-content-between mb-10 mb-md-0">
                        <div class="col-12 col-md-auto mfliud">
                            <button type="submit" class="btn stretched-link borders">Update Cart</button>
                        </div>
                    </div>
                </form>
                
                <script>
                    function removeItem(productId) {
                        const formData = new FormData();
                        formData.append('remove_item', productId);
                        formData.append('_token', '{{ csrf_token() }}'); // Add CSRF token
                
                        fetch('{{ route('cart.update') }}', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            if (response.ok) {
                                return response.json();
                            }
                            throw new Error('Network response was not ok.');
                        })
                        .then(data => {
                            if (data.success) {
                                // Item removed successfully; reload the cart
                                window.location.reload();
                            } else {
                                console.error(data.message); // Log the error message
                                alert(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('There has been a problem with your fetch operation:', error);
                        });
                    }
                </script>
                
            </div>

            <div class="col-12 col-md-12 col-lg-4">
                <div class="card mb-4 gray mfliud">
                    <div class="card-body">
                        <ul class="list-group list-group-sm list-group-flush-y list-group-flush-x">
                            <li class="list-group-item d-flex text-dark fs-sm ft-regular">
                                <span>Subtotal</span>
                                <span class="ml-auto text-dark ft-medium">
                                     {{ number_format(array_reduce(session('cart', []), fn($total, $item) => $total + $item['price'] * $item['quantity'], 0), 2) }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex text-dark fs-sm ft-regular">
                                <span>Tax</span>
                                <span class="ml-auto text-dark ft-medium"> 10.10</span>
                            </li>
                            <li class="list-group-item d-flex text-dark fs-sm ft-regular">
                                <span>Total</span>
                                <span class="ml-auto text-dark ft-medium">
                                     {{ number_format(array_reduce(session('cart', []), fn($total, $item) => $total + $item['price'] * $item['quantity'], 0) + 10.1, 2) }}
                                </span>
                            </li>
                            <li class="list-group-item fs-sm text-center">
                                Shipping cost calculated at Checkout *
                            </li>
                        </ul>
                    </div>
                </div>

                <a class="btn btn-block btn-dark mb-3" href="{{ route('front.checkout') }}">Proceed to Checkout</a>
                <a class="btn-link text-dark ft-medium" href="{{ route('front.home') }}">
                    <i class="ti-back-left mr-2"></i> Continue Shopping
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
