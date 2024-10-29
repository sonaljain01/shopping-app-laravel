@extends('front.layouts.app')
@section('content')
    <section class="bg-cover" style="background:url({{ asset('front-assets/img/banner-2.png') }}) no-repeat;">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-xl-12">
                    <div class="text-left py-5 mt-3 mb-3">
                        <h1 class="ft-medium mb-3">Shop</h1>
                        <ul class="shop_categories_list m-0 p-0">
                            <li><a href="#">Men</a></li>
                            <li><a href="#">Speakers</a></li>
                            <li><a href="#">Women</a></li>
                            <li><a href="#">Accessories</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="middle">
        <div class="container">
            <div class="row">
                @include('front.layouts.sidebar')

                <div class="col-xl-9 col-lg-8">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="border mb-3 mfliud">
                                <div class="row align-items-center py-2 m-0">
                                    <div class="col-xl-3 col-lg-4 col-md-5">
                                        <h6 class="mb-0">
                                            {{ $products->count() > 0 ? $products->count() . ' Items Found' : 'No Items Found' }}
                                        </h6>
                                    </div>

                                    <div class="col-xl-9 col-lg-8 col-md-7">
                                        <div class="filter_wraps d-flex align-items-center justify-content-end">
                                            <form id="sortForm" method="GET" action="{{ route('front.shop') }}">
                                                <select class="custom-select simple" id="sort_by" name="sort"
                                                    onchange="document.getElementById('sortForm').submit();">
                                                    <option value="" selected="">Default Sorting</option>
                                                    <option value="price_low_high"
                                                        {{ request('sort') == 'price_low_high' ? 'selected' : '' }}>Sort by
                                                        price: Low price</option>
                                                    <option value="price_high_low"
                                                        {{ request('sort') == 'price_high_low' ? 'selected' : '' }}>Sort by
                                                        price: High price</option>
                                                    <option value="rating"
                                                        {{ request('sort') == 'rating' ? 'selected' : '' }}>Sort by rating
                                                    </option>
                                                    <option value="trending"
                                                        {{ request('sort') == 'trending' ? 'selected' : '' }}>Sort by
                                                        trending</option>
                                                </select>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($products->count() > 0)
                        <div class="row align-items-center rows-products">
                            @foreach ($products as $product)
                                <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                    <div class="product_grid card b-0">
                                        @if ($product->created_at->diffInHours(now()) < 48)
                                            <div
                                                class="badge bg-info text-white position-absolute ft-regular ab-left text-upper">
                                                New</div>
                                        @endif
                                        <div class="card-body p-0">
                                            <div class="shop_thumb position-relative">
                                                <a class="card-img-top d-block overflow-hidden"
                                                    href="{{ route('product.show', $product->slug) }}">
                                                    <img class="card-img-top"
                                                        src="{{ asset($product->product_images->first()->image ?? 'front-asset/img/path-to-placeholder-image/placeholder.jpg') }}"
                                                        alt="{{ $product->title }}">
                                                </a>
                                                <div
                                                    class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                                    <a href="#" data-toggle="modal" data-target="#quickview"
                                                        class="text-white fs-sm ft-medium">
                                                        <i class="fas fa-eye mr-1"></i>Quick View
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer b-0 p-0 pt-2 bg-white">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <div class="text-left">
                                                    <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1">
                                                        <a
                                                            href="{{ route('product.show', $product->slug) }}">{{ $product->title }}</a>
                                                    </h5>
                                                    <div class="elis_rty">
                                                        <span
                                                            class="ft-bold text-dark fs-sm">Rs.{{ $product->price }}</span>
                                                    </div>
                                                </div>
                                                <button class="btn auto btn_love snackbar-wishlist"><i
                                                        class="far fa-heart"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>No products available</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
    {{-- @foreach($products as $product)
    {{-- @php 
    echo $product;
    @endphp 
    @endforeach --}}

    @foreach ($products as $product)
        {{-- Quick View Modal --}}
        <div class="modal fade lg-modal" id="quickview" tabindex="-1" role="dialog" aria-labelledby="quickviewmodal"
            aria-hidden="true">
            <div class="modal-dialog modal-xl login-pop-form" role="document">
                <div class="modal-content" id="quickviewmodal">
                    <div class="modal-headers">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="ti-close"></span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="quick_view_wrap">
                            

                            <div class="quick_view_capt">

                                <div class="row">

                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                        <div class="col-md-6">
                                            @if (count($product->product_images) > 0)
                                                <img src="{{ asset($product->product_images[0]->image) }}"
                                                    class="img-fluid" />
                                            
                                            @endif
                                        
                                    </div>


                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">


                                        <div class="prd_details">
                                            <div class="prt_01 mb-1">
                                                <span
                                                    class="text-success bg-light-success rounded px-2 py-1">{{ $product->category->name }}</span>
                                            </div>
                                            <div class="prt_02 mb-3">
                                                <h2 class="ft-bold mb-1">{{ $product->title }}</h2>

                                                <div class="text-left">
                                                    <div
                                                        class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
                                                        @for ($i = 0; $i < 5; $i++)
                                                            <i
                                                                class="fas fa-star {{ $i < $product->rating ? 'filled' : '' }}"></i>
                                                        @endfor
                                                        <span class="small">({{ $product->reviews_count }}
                                                            Reviews)</span>
                                                    </div>
                                                    <div class="elis_rty">
                                                        <span class="ft-medium text-muted line-through fs-md mr-2">Rs.{{ $product->compare_price }}</span>
                                                        <span class="ft-bold theme-cl fs-lg mr-2">Rs.{{ $product->price }}</span>
                                                        
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="prt_03 mb-4">
                                                <p>{{ $product->description }}</p>
                                            </div>


                                            <div class="prt_04 mb-4">
                                                <div class="form-row mb-7">
                                                    <div class="col-12 col-lg-auto">
                                                        Category:
                                                    </div>
                                                    <b class="col-12 col-lg">
                                                        {{ $product->category->name }}
                                                    </b>
                                                </div>
                                            </div>
                                            <div class="prt_04 mb-4">
                                                <div class="form-row mb-7">
                                                    <div class="col-12 col-lg-auto">
                                                        SKU:
                                                    </div>
                                                    <b class="col-12 col-lg">
                                                        {{ $product->sku }}
                                                    </b>
                                                </div>
                                            </div>

                                            <div class="prt_05 mb-4">
                                                <div class="form-row mb-7">
                                                    <div class="col-12 col-lg-auto">
                                                        <!-- Quantity -->
                                                        <select class="mb-2 custom-select">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <option value="{{ $i }}">
                                                                    {{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-lg">
                                                        <!-- Submit -->
                                                        <form action="{{ route('cart.add', $product->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-block custom-height bg-dark mb-2">
                                                                <i class="lni lni-shopping-basket mr-2"></i>Add to
                                                                Cart
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <div class="col-12 col-lg-auto">
                                                        <!-- Wishlist -->
                                                        <button
                                                            class="btn custom-height btn-default btn-block mb-2 text-dark"
                                                            data-toggle="button">
                                                            <i class="lni lni-heart mr-2"></i>Wishlist
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="prt_06">
                                                <p class="mb-0 d-flex align-items-center">
                                                    <span class="mr-4">Share:</span>
                                                    <a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted mr-2"
                                                        href="#!">
                                                        <i class="fab fa-twitter position-absolute"></i>
                                                    </a>
                                                    <a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted mr-2"
                                                        href="#!">
                                                        <i class="fab fa-facebook-f position-absolute"></i>
                                                    </a>
                                                    <a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted"
                                                        href="#!">
                                                        <i class="fab fa-pinterest-p position-absolute"></i>
                                                    </a>
                                                </p>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    @endforeach
            </div>
@endsection
