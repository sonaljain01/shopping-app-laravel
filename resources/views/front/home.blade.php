@extends('front.layouts.app')

@section('content')
    <section class="middle">
        <div class="container">
            <div class="row">

                @include('front.layouts.sidebar')
                <section class="section-4 pt-5">
                    <div class="container">
                        <div class="section-title">
                            <h2>Featured Products</h2>
                        </div>
                        <div class="row pb-3">
                            @if ($featuredProducts->count() > 0)
                                @foreach ($featuredProducts as $product)
                                    <div class="col-md-3">
                                        <div class="card product-card">
                                            <div class="product-image position-relative">
                                                <a href="{{ route('product.show', $product->slug) }}" class="product-img">
                                                    @if (count($product->product_images) > 0)
                                                        <img class="card-img-top"
                                                            src="{{ asset($product->product_images[0]->image) }}"
                                                            alt="{{ $product->title }}" />
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
                </section>

                <section class="section-4 pt-5">
                    <div class="container">
                        <div class="section-title">
                            <h2>Latest Products</h2>
                        </div>
                        <div class="row pb-3">
                            @if ($latestProducts->count() > 0)
                                @foreach ($latestProducts as $product)
                                    <div class="col-md-3">
                                        <div class="card product-card">
                                            <div class="product-image position-relative">
                                                <a href="{{ route('product.show', $product->slug) }}" class="product-img">
                                                    @if (count($product->product_images) > 0)
                                                        <img class="card-img-top"
                                                            src="{{ asset($product->product_images[0]->image) }}"
                                                            alt="{{ $product->title }}" />
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
                </section>

                <div class="col-xl-12 col-lg-12 col-md-12 text-center">
                    <a href="#" class="btn stretched-link borders m-auto"><i class="lni lni-reload mr-2"></i>Load
                        More</a>
                </div>
            </div>
        </div>
    </section>
@endsection
