@extends('front.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center justify-content-between">

            @include('front.layouts.sidebar_two')

            <div class="col-12 col-md-12 col-lg-8 col-xl-8 text-center">
                <!-- row -->
                <div class="row align-items-center">

                    <!-- Single -->
                    {{-- <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                        <div class="product_grid card b-0">
                            <div class="badge bg-success text-white position-absolute ft-regular ab-left text-upper">Sale
                            </div>
                            <button class="btn btn_love position-absolute ab-right theme-cl"><i
                                    class="fas fa-times"></i></button>
                            <div class="card-body p-0">
                                <div class="shop_thumb position-relative">
                                    <a class="card-img-top d-block overflow-hidden" href="shop-single-v1.html"><img
                                            class="card-img-top" src="assets/img/product/1.jpg" alt="..."></a>
                                    <div
                                        class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                        <div class="edlio"><a href="#" data-toggle="modal" data-target="#quickview"
                                                class="text-white fs-sm ft-medium"><i class="fas fa-eye mr-1"></i>Quick
                                                View</a></div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="card-footers b-0 pt-3 px-2 bg-white d-flex align-items-start justify-content-center">
                                <div class="text-left">
                                    <div class="text-center">
                                        <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1"><a href="shop-single-v1.html">Half
                                                Running Set</a></h5>
                                        <div class="elis_rty"><span class="ft-bold fs-md text-dark">$119.00</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="row">
                        @foreach ($wishlists as $wishlist)
                            @if ($wishlist->product)
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <div class="product_grid card b-0">
                                        
                                        <form action="{{ route('wishlist.remove', $wishlist->id) }}" method="POST"
                                            class="position-absolute ab-right theme-cl">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn_love"><i
                                                    class="fas fa-times"></i></button>
                                        </form>
                                        <div class="card-body p-0">
                                            <div class="shop_thumb position-relative">
                                                <a class="card-img-top d-block overflow-hidden"
                                                    href="{{ route('product.show', $wishlist->product->slug) }}">
                                                    <img class="card-img-top"
                                                        src="{{ asset($wishlist->product->product_images->first()->image ?? 'front-asset/img/path-to-placeholder-image/placeholder.jpg') }}"
                                                        alt="{{ $wishlist->product->title }}">
                                                </a>
                                                {{-- <div
                                                    class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                                    <div class="edlio">
                                                        <a href="#" data-toggle="modal" data-target="#quickview"
                                                            class="text-white fs-sm ft-medium">
                                                            <i class="fas fa-eye mr-1"></i>Quick View
                                                        </a>
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                        <div
                                            class="card-footers b-0 pt-3 px-2 bg-white d-flex align-items-start justify-content-center">
                                            <div class="text-center">
                                                <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1">
                                                    <a
                                                        href="{{ route('product.show', $wishlist->product->slug) }}">{{ $wishlist->product->title }}</a>
                                                </h5>
                                                <div class="elis_rty">
                                                    <span
                                                        class="ft-bold fs-md text-dark">Rs.{{ $wishlist->product->price }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                </div>
                <!-- row -->
            </div>

        </div>
    </div>
@endsection
