@extends('front.layouts.app')

@section('content')
    <section class="middle">
        <div class="container">
            <div class="row">
                @include('front.layouts.sidebar')
                {{-- // display the selected products with their images --}}
                <div class="col-md-9">
                    <div class="product-list">
                        <h3>{{ $products->count() }} Items Found</h3>
                        <div class="row">
                            @if ($products->count() > 0)
                                @foreach ($products as $product)
                                    <div class="col-md-4">
                                        <div class="card product-card">
                                            <div class="product-image position-relative">
                                                <a href="{{ route('product.show', $product->slug) }}" class="product-img">
                                                    @if (count($product->product_images) > 0)
                                                        <img class="card-img-top"
                                                            src="{{ asset($product->product_images[0]->image) }}" />
                                                    @endif
                                                </a>

                                                <a class="whishlist" href="222"><i class="far fa-heart"></i></a>

                                                <div class="product-action">
                                                    <a class="btn btn-dark" href="#">
                                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="card-body text-center mt-3">
                                                <a class="h6 link"
                                                    href="{{ route('product.show', $product->slug) }}">{{ $product->title }}</a>
                                                <div class="price mt-2">
                                                    <span class="h5"><strong>${{ $product->price }}</strong></span>

                                                    @if ($product->compare_price > 0)
                                                        <span
                                                            class="h6 text-underline"><del>${{ $product->compare_price }}</del></span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
