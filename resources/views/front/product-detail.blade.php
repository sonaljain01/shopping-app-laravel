@extends('front.layouts.app')

@section('content')
<section class="section-6 pt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <!-- Product Images -->
                <div class="product-gallery">
                    @if ($product->product_images->count() > 0)
                        @foreach ($product->product_images as $image)
                            <img src="{{ asset($image->image) }}" class="img-fluid mb-3" alt="{{ $product->title }}">
                        @endforeach
                    @else
                        <img src="{{ asset('default-placeholder.png') }}" class="img-fluid mb-3" alt="No Image">
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <!-- Product Details -->
                <h1 class="product-title">{{ $product->title }}</h1>
                <div class="price">
                    <h3>${{ $product->price }}</h3>
                    @if ($product->compare_price > 0)
                        <span class="text-muted"><del>${{ $product->compare_price }}</del></span>
                    @endif
                </div>
                <div class="product-description mt-4">
                    <p>{{ $product->description }}</p>
                </div>
                
                <!-- Product Information -->
                <ul class="list-unstyled mt-4">
                    <li><strong>Category:</strong> {{ $product->category->name }}</li>
                    <li><strong>Brand:</strong> {{ $product->brand->name }}</li>
                    <li><strong>Stock:</strong> {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}</li>
                    <li><strong>SKU:</strong> {{ $product->sku }}</li>
                    {{-- <li><strong>Stock:</strong> {{ $product->track_qty }}</li> --}}
                </ul>

                <!-- Add to Cart Button -->
                <div class="mt-4">
                    <a href="#" class="btn btn-dark">
                        <i class="fa fa-shopping-cart"></i> Add to Cart
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
