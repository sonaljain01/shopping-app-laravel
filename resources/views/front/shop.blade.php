@extends('front.layouts.app')

@section('content')
<div class="container">
    <div class="row align-items-center justify-content-between">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Shop</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Women's</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
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
                                                    <div class="edlio">
                                                        <a href="#" data-toggle="modal" data-target="#quickview"
                                                            class="text-white fs-sm ft-medium quick-view-btn"
                                                            data-name="{{ $product->title }}"
                                                            data-price="{{ $product->price }}"
                                                            data-id="{{ $product->id }}"
                                                            data-description="{{ $product->description }}"
                                                            data-gallery="{{ json_encode($product->gallery) }}"
                                                            data-category="{{ $product->category->name }}"
                                                            data-old-price="{{ $product->compare_price }}"
                                                            data-new-price="{{ $product->price }}">
                                                            <i class="fas fa-eye mr-1"></i>Quick View
                                                        </a>
                                                    </div>

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
                                                <form action="{{ route('wishlist.add') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <button type="submit" class="btn auto btn_love">
                                                        <i class="far fa-heart"></i>
                                                    </button>
                                                </form>
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


    {{-- @foreach ($products as $product)
       
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
                                                            <span
                                                                class="ft-medium text-muted line-through fs-md mr-2">Rs.{{ $product->compare_price }}</span>
                                                            <span
                                                                class="ft-bold theme-cl fs-lg mr-2">Rs.{{ $product->price }}</span>

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
            </div>
        </div>

    @endforeach --}}
    <!-- Quick View Modal -->
    {{-- <div class="modal fade lg-modal" id="quickview" tabindex="-1" role="dialog" aria-labelledby="quickviewmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="quickviewmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <div class="quick_view_wrap">
                        <div class="quick_view_thmb">
                            <div class="quick_view_slide">
                                <div class="single_view_slide">
                                    <img src="" class="img-fluid" id="quickview_image" alt="" />
                                </div>
                               
                            </div>
                        </div>
    
                        <div class="quick_view_capt">
                            <div class="prd_details">
                                <div class="prt_01 mb-1">
                                    <span class="text-light bg-info rounded px-2 py-1" id="quickview_category"></span>
                                </div>
                                <div class="prt_02 mb-2">
                                    <h2 class="ft-bold mb-1" id="quickview_title"></h2>
                                    <div class="text-left">
                                        <div class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
                                            <span id="quickview_rating"></span>
                                            <span class="small" id="quickview_reviews"></span>
                                        </div>
                                        <div class="elis_rty">
                                            <span class="ft-medium text-muted line-through fs-md mr-2" id="quickview_original_price"></span>
                                            <span class="ft-bold theme-cl fs-lg mr-2" id="quickview_price"></span>
                                            <span class="ft-regular text-danger bg-light-danger py-1 px-2 fs-sm" id="quickview_stock_status"></span>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="prt_03 mb-3">
                                    <p id="quickview_description"></p>
                                </div>
    
                                <div class="prt_04 mb-2">
                                    <p class="d-flex align-items-center mb-0 text-dark ft-medium">Color:</p>
                                    <div class="text-left" id="quickview_colors"></div>
                                </div>
    
                                <div class="prt_04 mb-4">
                                    <p class="d-flex align-items-center mb-0 text-dark ft-medium">Size:</p>
                                    <div class="text-left" id="quickview_sizes"></div>
                                </div>
    
                                <div class="prt_05 mb-4">
                                    <div class="form-row mb-7">
                                        <div class="col-12 col-lg-auto">
                                            <select class="mb-2 custom-select" id="quickview_quantity">
                                                <!-- Quantity options will be populated dynamically -->
                                                <option value="1" selected="">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg">
                                            <button type="button" class="btn btn-block custom-height bg-dark mb-2" id="quickview_add_to_cart">
                                                <i class="lni lni-shopping-basket mr-2"></i>Add to Cart 
                                            </button>
                                        </div>
                                        <div class="col-12 col-lg-auto">
                                            <button class="btn custom-height btn-default btn-block mb-2 text-dark" id="quickview_add_to_wishlist">
                                                <i class="lni lni-heart mr-2"></i>Wishlist
                                            </button>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="prt_06">
                                    <p class="mb-0 d-flex align-items-center">
                                        <span class="mr-4">Share:</span>
                                        <a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted mr-2" href="#!">
                                            <i class="fab fa-twitter position-absolute"></i>
                                        </a>
                                        <a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted mr-2" href="#!">
                                            <i class="fab fa-facebook-f position-absolute"></i>
                                        </a>
                                        <a class="d-inline-flex align-items-center justify-content-center p-3 gray circle fs-sm text-muted" href="#!">
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
    </div> --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.quick-view-btn').forEach(function (button) {
                button.addEventListener('click', function () {
    
                    // Retrieve product data from data attributes
                    const productName = this.getAttribute('data-name');
                    const productPrice = this.getAttribute('data-price');
                    const productDescription = this.getAttribute('data-description');
                    const productGallery = JSON.parse(this.getAttribute('data-gallery')); // Ensure this is an array of image objects
                    const productCategory = this.getAttribute('data-category');
                    const productReviews = this.getAttribute('data-reviews');
                    const oldPrice = this.getAttribute('data-old-price');
                    const newPrice = this.getAttribute('data-price');
                    const id = this.getAttribute('data-id');
    
                    // Populate modal with product data
                    document.querySelector('#quickviewmodal .ft-bold.mb-1').innerText = productName;
                    document.querySelector('#quickviewmodal .ft-bold.theme-cl.fs-lg.mr-2').innerText = `₹${newPrice}`;
                    document.querySelector('#quickviewmodal .ft-medium.text-muted.line-through.fs-md.mr-2').innerText = `₹${oldPrice}`;
                    document.querySelector('#quickviewmodal .prt_03.mb-3 p').innerText = productDescription;
                    document.querySelector("#category").innerText = productCategory;
    
                    // Populate the gallery
                    let galleryHTML = '';
                    if (Array.isArray(productGallery) && productGallery.length > 0) {
                        productGallery.forEach(function (image) {
                            galleryHTML += `<div class="single_view_slide"><img src="${image.image}" class="img-fluid" alt="" /></div>`;
                        });
                    } else {
                        galleryHTML = '<p>No images available.</p>'; // Handle case with no images
                    }
                    document.querySelector('.quick_view_slide').innerHTML = galleryHTML;
    
                    // Event listener for add to cart button
                    document.querySelector('#btnadd').onclick = function () {
                        const data = {
                            id: id,
                            quantity: 1,
                            price: productPrice
                        };
                        addToCart(data);
                    };
                });
            });
    
            async function addToCart(params) {
                try {
                    const res = await fetch(`{{route('cart.add', '')}}/${params.id}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            product_id: params.id,
                            quantity: params.quantity,
                            price: params.price
                        }),
                    });
    
                    const data = await res.json();
                    if (!data.status) {
                        alert(data.message);
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    console.error('Error adding to cart:', error);
                    alert('An error occurred while adding to cart.');
                }
            }
        });
    </script> --}}


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
                        <div class="quick_view_thmb">
                            <div class="quick_view_slide">
                            </div>
                        </div>

                        <div class="quick_view_capt">
                            <div class="prd_details">

                                <div class="prt_01 mb-1"><span class="text-light bg-info rounded px-2 py-1"
                                        id="category">{{ $product->category->name }}</span></div>
                                <div class="prt_02 mb-2">
                                    <h2 class="ft-bold mb-1">{{ $product->title }}</h2>
                                    <div class="text-left">
                                        <div class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star"></i>
                                            <span class="small">(412 Reviews)</span>
                                        </div>
                                        <div class="elis_rty"><span
                                                class="ft-medium text-muted line-through fs-md mr-2">$199</span><span
                                                class="ft-bold theme-cl fs-lg mr-2">$110</span></div>
                                    </div>
                                </div>

                                <div class="prt_03 mb-3">
                                    <p>{{ $product->description }}</p>
                                </div>

                                <div class="prt_05 mb-4">
                                    <div class="form-row mb-7">
                                        <div class="col-12 col-lg-auto">
                                            <!-- Quantity -->
                                            <select class="mb-2 custom-select">
                                                <option value="1" selected="">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-lg">
                                            <!-- Add to Cart Form -->
                                            <form id="addToCartForm" method="POST" >
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <!-- Set default quantity to 1 -->
                                                <input type="hidden" name="price" value="{{ $product->price }}">

                                                <!-- Submit Button -->
                                                <button type="submit" class="btn btn-block custom-height bg-dark mb-2">
                                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                                </button>
                                            </form>
                                        </div>

                                        <div class="col-12 col-lg-auto">
                                            <!-- Wishlist -->
                                            <form id="wishlistForm" method="POST" action="{{ route('wishlist.add') }}">
                                                @csrf
                                                <input type="hidden" name="product_id" id="wishlist-product-id" value="">
                                                <button type="submit" class="btn custom-height btn-default btn-block mb-2 text-dark">
                                                    <i class="lni lni-heart mr-2"></i> Wishlist
                                                </button>
                                            </form>
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
    <!-- End Modal -->


    <a id="back2Top" class="top-scroll" title="Back to top" href="#"><i class="ti-arrow-up"></i></a>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.quick-view-btn').forEach(function(button) {
                button.addEventListener('click', function() {

                    const productId = this.getAttribute('data-id');
                    const productName = this.getAttribute('data-name');
                    const productPrice = this.getAttribute('data-price');
                    const productDescription = this.getAttribute('data-description');
                    const productGallery = JSON.parse(this.getAttribute('data-gallery'));
                    const productCategory = this.getAttribute('data-category');
                    const productReviews = this.getAttribute('data-reviews');
                    const oldPrice = this.getAttribute('data-old-price');
                    const newPrice = this.getAttribute('data-new-price');
                    const form = document.getElementById('addToCartForm');
                    form.action = "{{ route('cart.add', '') }}/" + productId;
                    const wishlistForm  = document.getElementById('wishlistForm');
                    wishlistForm.action = "{{ route('wishlist.add', '') }}";                   
                    
                    document.querySelector('#quickviewmodal .ft-bold.mb-1').innerText = productName;
                    document.querySelector('#quickviewmodal .ft-bold.theme-cl.fs-lg.mr-2')
                        .innerText = `Rs.${newPrice}`;
                    document.querySelector(
                            '#quickviewmodal .ft-medium.text-muted.line-through.fs-md.mr-2')
                        .innerText = `Rs.${oldPrice}`;
                    document.querySelector('#quickviewmodal .prt_03.mb-3 p').innerText =
                        productDescription;
                    document.querySelector("#category").innerText = productCategory;
                    //for id
                    document.querySelector('#productId').value = productId;
                    document.querySelector('#wishlist-product-id').value = productId;
                    



                    let galleryHTML = '';
                    const basePath = 'public/uploads/product/'
                    productGallery.forEach(function(imageFileName) {
                        galleryHTML +=
                            `<div class="single_view_slide"><img src="${basePath + imageFileName}" class="img-fluid" alt="" /></div>`;
                    });
                    document.querySelector('.quick_view_slide').innerHTML = galleryHTML;

                    // document.querySelector('#btnAddToCart').onclick = function() {
                    //     const quantity = document.querySelector('#productQuantity').value;
                    //     addToCart({
                    //         id: productId,
                    //         quantity: quantity,
                    //         price: productPrice
                    //     });
                    // };

                });
            });


            // async function addToCart(data) {
            //     const url = document.getElementById('btnAddToCart').getAttribute('data-url');

            //     try {
            //         const response = await fetch(url, {
            //             method: 'POST',
            //             headers: {
            //                 'Content-Type': 'application/json',
            //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
            //                     .getAttribute('content')
            //             },
            //             body: JSON.stringify({
            //                 product_id: data.id,
            //                 quantity: data.quantity,
            //                 price: data.price
            //             })
            //         });

            //         const result = await response.json();
            //         if (result.status) {
            //             alert(result.message); // Product successfully added
            //         } else {
            //             alert('Error adding product to cart: ' + result.message);
            //         }
            //     } catch (error) {
            //         console.error('Error:', error);
            //         alert('An error occurred while adding the product to the cart.');
            //     }
            // }

        });
    </script>





@endsection
