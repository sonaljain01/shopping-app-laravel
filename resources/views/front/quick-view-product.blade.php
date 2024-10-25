<!-- partials/quick-view-product.blade.php -->

<div class="row">
    <div class="col-md-6">
        @if (count($product->product_images) > 0)
            <img class="img-fluid" src="{{ asset($product->product_images[0]->image) }}" alt="{{ $product->title }}">
        @else
            <img class="img-fluid" src="{{ asset('path-to-placeholder-image/placeholder.jpg') }}" alt="No Image Available">
        @endif
    </div>
    <div class="col-md-6">
        <h3>{{ $product->title }}</h3>
        <p class="text-muted">${{ $product->price }}</p>
        <p>{{ $product->description }}</p>
        <!-- Add more product details as needed -->
    </div>
</div>
