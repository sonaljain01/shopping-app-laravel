@extends('front.layouts.app')

@section('content')
    <section class="middle">
        <div class="container">
            <div class="row">
                @include('front.layouts.sidebar')


                {{-- // display the selected products with their images --}}

                <div class="col-xl-9 col-lg-8 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="border mb-3 mfliud">
                                <div class="row align-items-center py-2 m-0">
                                    <div class="col-xl-3 col-lg-4 col-md-5 col-sm-12">
                                        @if (count($products) > 0)
                                            <h6 class="mb-0">{{ $products->count() }} Items Found</h6>
                                        @else
                                            <h6 class="mb-0">No Items Found</h6>
                                        @endif


                                    </div>

                                    <div class="col-xl-9 col-lg-8 col-md-7 col-sm-12">
                                        <div class="filter_wraps d-flex align-items-center justify-content-end m-start">
                                            <div class="single_fitres mr-2 br-right">
                                                <select class="custom-select simple">
                                                    <option value="1" selected="">Default Sorting</option>
                                                    <option value="2">Sort by price: Low price</option>
                                                    <option value="3">Sort by price: Hight price</option>
                                                    <option value="4">Sort by rating</option>
                                                    <option value="5">Sort by trending</option>
                                                </select>
                                            </div>
                                            <div class="single_fitres">
                                                <a href="shop-style-5.html" class="simple-button active mr-1"><i
                                                        class="ti-layout-grid2"></i></a>
                                                <a href="shop-list-sidebar.html" class="simple-button"><i
                                                        class="ti-view-list"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Single -->
                    @if ($products->count() > 0)
                        <div class="row align-items-center rows-products">
                            @foreach ($products as $product)
                                <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                                    <div class="product_grid card b-0">
                                        <!-- New Badge -->
                                        <div class="badge bg-info text-white position-absolute ft-regular ab-left text-upper">New</div>
                                        <div class="card-body p-0">
                                            <div class="shop_thumb position-relative">
                                                <!-- Product Image -->
                                                @if (count($product->product_images) > 0)
                                                    <a class="card-img-top d-block overflow-hidden"
                                                        href="{{ route('product.show', $product->id) }}">
                                                        <img class="card-img-top"
                                                            src="{{ asset($product->product_images[0]->image) }}"
                                                            alt="{{ $product->title }}">
                                                    </a>
                                                @else
                                                    <!-- Placeholder Image if no product image is available -->
                                                    <a class="card-img-top d-block overflow-hidden"
                                                        href="{{ route('product.show', $product->id) }}">
                                                        <img class="card-img-top"
                                                            src="{{ asset('front-asset/img/path-to-placeholder-image/placeholder.jpg') }}"
                                                            alt="No Image Available">
                                                    </a>
                                                @endif

                                                <!-- Quick View Button -->
                                                <div
                                                    class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                                    <div class="edlio">
                                                        <a href="#" data-toggle="modal" data-target="#quickview"
                                                            class="text-white fs-sm ft-medium">
                                                            <i class="fas fa-eye mr-1"></i>Quick View
                                                        </a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="card-footer b-0 p-0 pt-2 bg-white">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <div class="text-left">
                                                    <div class="form-check form-option form-check-inline mb-1">
                                                        <input class="form-check-input" type="radio" name="color2"
                                                            id="white2">
                                                        <label class="form-option-label small rounded-circle"
                                                            for="white2"><span
                                                                class="form-option-color rounded-circle blc5"></span></label>
                                                    </div>
                                                    <div class="form-check form-option form-check-inline mb-1">
                                                        <input class="form-check-input" type="radio" name="color2"
                                                            id="blue2">
                                                        <label class="form-option-label small rounded-circle"
                                                            for="blue2"><span
                                                                class="form-option-color rounded-circle blc2"></span></label>
                                                    </div>
                                                    <div class="form-check form-option form-check-inline mb-1">
                                                        <input class="form-check-input" type="radio" name="color2"
                                                            id="yellow2">
                                                        <label class="form-option-label small rounded-circle"
                                                            for="yellow2"><span
                                                                class="form-option-color rounded-circle blc6"></span></label>
                                                    </div>
                                                    <div class="form-check form-option form-check-inline mb-1">
                                                        <input class="form-check-input" type="radio" name="color2"
                                                            id="pink2">
                                                        <label class="form-option-label small rounded-circle"
                                                            for="pink2"><span
                                                                class="form-option-color rounded-circle blc4"></span></label>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <button class="btn auto btn_love snackbar-wishlist"><i
                                                            class="far fa-heart"></i></button>
                                                </div>
                                            </div>


                                            <!-- Product Title & Price -->
                                            <div class="text-left">
                                                <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1">
                                                    <a
                                                        href="{{ route('product.show', $product->slug) }}">{{ $product->title }}</a>
                                                </h5>
                                                <div class="elis_rty">
                                                    <span class="ft-bold text-dark fs-sm">${{ $product->price }}</span>


                                                </div>
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
@endsection
