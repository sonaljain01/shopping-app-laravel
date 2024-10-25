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
                                                <form id="sortForm" method="GET" action="{{ route('front.shop') }}">
                                                    <select class="custom-select simple" id="sort_by" name="sort" onchange="document.getElementById('sortForm').submit();">
                                                        <option value="" selected="">Default Sorting</option>
                                                        <option value="price_low_high" {{ request('sort') == 'price_low_high' ? 'selected' : '' }}>Sort by price: Low price</option>
                                                        <option value="price_high_low" {{ request('sort') == 'price_high_low' ? 'selected' : '' }}>Sort by price: High price</option>
                                                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Sort by rating</option>
                                                        <option value="trending" {{ request('sort') == 'trending' ? 'selected' : '' }}>Sort by trending</option>
                                                    </select>
                                                </form>
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
                                        <div
                                            class="badge bg-info text-white position-absolute ft-regular ab-left text-upper">
                                            New</div>
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

                <div class="modal fade lg-modal" id="quickview" tabindex="-1" role="dialog"
                    aria-labelledby="quickviewmodal" aria-hidden="true">
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

                                        {{-- <div class="prd_details">
                                            
                                            <div class="prt_01 mb-1"><span class="text-light bg-info rounded px-2 py-1">{{ $product->category->name }}</span></div>
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
                                                    <div class="elis_rty"><span class="ft-medium text-muted line-through fs-md mr-2">$199</span><span class="ft-bold theme-cl fs-lg mr-2">$110</span><span class="ft-regular text-danger bg-light-danger py-1 px-2 fs-sm">Out of Stock</span></div>
                                                </div>
                                            </div>
                                            
                                            <div class="prt_03 mb-3">
                                                <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores.</p>
                                            </div>
                                            
                                            <div class="prt_04 mb-2">
                                                <p class="d-flex align-items-center mb-0 text-dark ft-medium">Color:</p>
                                                <div class="text-left">
                                                    <div class="form-check form-option form-check-inline mb-1">
                                                        <input class="form-check-input" type="radio" name="color8" id="white8">
                                                        <label class="form-option-label rounded-circle" for="white8"><span class="form-option-color rounded-circle blc7"></span></label>
                                                    </div>
                                                    <div class="form-check form-option form-check-inline mb-1">
                                                        <input class="form-check-input" type="radio" name="color8" id="blue8">
                                                        <label class="form-option-label rounded-circle" for="blue8"><span class="form-option-color rounded-circle blc2"></span></label>
                                                    </div>
                                                    <div class="form-check form-option form-check-inline mb-1">
                                                        <input class="form-check-input" type="radio" name="color8" id="yellow8">
                                                        <label class="form-option-label rounded-circle" for="yellow8"><span class="form-option-color rounded-circle blc5"></span></label>
                                                    </div>
                                                    <div class="form-check form-option form-check-inline mb-1">
                                                        <input class="form-check-input" type="radio" name="color8" id="pink8">
                                                        <label class="form-option-label rounded-circle" for="pink8"><span class="form-option-color rounded-circle blc3"></span></label>
                                                    </div>
                                                    <div class="form-check form-option form-check-inline mb-1">
                                                        <input class="form-check-input" type="radio" name="color8" id="red">
                                                        <label class="form-option-label rounded-circle" for="red"><span class="form-option-color rounded-circle blc4"></span></label>
                                                    </div>
                                                    <div class="form-check form-option form-check-inline mb-1">
                                                        <input class="form-check-input" type="radio" name="color8" id="green">
                                                        <label class="form-option-label rounded-circle" for="green"><span class="form-option-color rounded-circle blc6"></span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="prt_04 mb-4">
                                                <p class="d-flex align-items-center mb-0 text-dark ft-medium">Size:</p>
                                                <div class="text-left pb-0 pt-2">
                                                    <div class="form-check size-option form-option form-check-inline mb-2">
                                                        <input class="form-check-input" type="radio" name="size" id="28" checked="">
                                                        <label class="form-option-label" for="28">28</label>
                                                    </div>
                                                    <div class="form-check form-option size-option  form-check-inline mb-2">
                                                        <input class="form-check-input" type="radio" name="size" id="30">
                                                        <label class="form-option-label" for="30">30</label>
                                                    </div>
                                                    <div class="form-check form-option size-option  form-check-inline mb-2">
                                                        <input class="form-check-input" type="radio" name="size" id="32">
                                                        <label class="form-option-label" for="32">32</label>
                                                    </div>
                                                    <div class="form-check form-option size-option  form-check-inline mb-2">
                                                        <input class="form-check-input" type="radio" name="size" id="34">
                                                        <label class="form-option-label" for="34">34</label>
                                                    </div>
                                                    <div class="form-check form-option size-option  form-check-inline mb-2">
                                                        <input class="form-check-input" type="radio" name="size" id="36">
                                                        <label class="form-option-label" for="36">36</label>
                                                    </div>
                                                    <div class="form-check form-option size-option  form-check-inline mb-2">
                                                        <input class="form-check-input" type="radio" name="size" id="38">
                                                        <label class="form-option-label" for="38">38</label>
                                                    </div>
                                                    <div class="form-check form-option size-option  form-check-inline mb-2">
                                                        <input class="form-check-input" type="radio" name="size" id="40">
                                                        <label class="form-option-label" for="40">40</label>
                                                    </div>
                                                </div>
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
                                                        <!-- Submit -->
                                                        <button type="submit" class="btn btn-block custom-height bg-dark mb-2">
                                                            <i class="lni lni-shopping-basket mr-2"></i>Add to Cart 
                                                        </button>
                                                    </div>
                                                    <div class="col-12 col-lg-auto">
                                                        <!-- Wishlist -->
                                                        <button class="btn custom-height btn-default btn-block mb-2 text-dark" data-toggle="button">
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
                                            
                                        </div> --}}
                                        <div class="row">

                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                                <div class="col-md-6">
                                                    @if (count($product->product_images) > 0)
                                                        <img src="{{ asset($product->product_images[0]->image) }}"
                                                            class="img-fluid" />
                                                    @endif
                                                </div>
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
                                                                    class="ft-medium text-muted line-through fs-md mr-2">${{ $product->compare_price }}</span>
                                                                <span
                                                                    class="ft-bold theme-cl fs-lg mr-2">${{ $product->price }}</span>
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
                                                                <button type="submit"
                                                                    class="btn btn-block custom-height bg-dark mb-2">
                                                                    <i class="lni lni-shopping-basket mr-2"></i>Add to Cart
                                                                </button>
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
                            {{-- <div class="modal fade lg-modal" id="quickview" tabindex="-1" role="dialog"
                    aria-labelledby="quickviewmodal" aria-hidden="true">
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
                                                <img id="quickview-image" src="{{ asset('front-asset/img/product/1.jpg') }}"
                                                    class="img-fluid" alt="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="quick_view_capt">
                                        <div class="prd_details">
                                            <div class="prt_01 mb-1">
                                                <span id="quickview-category"
                                                    class="text-light bg-info rounded px-2 py-1"></span>
                                            </div>
                                            <div class="prt_02 mb-2">
                                                <h2 id="quickview-title" class="ft-bold mb-1"></h2>
                                                <div class="text-left">
                                                    <div
                                                        class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
                                                        <span id="quickview-rating"></span>
                                                        <span class="small" id="quickview-reviews"></span>
                                                    </div>
                                                    <div class="elis_rty">
                                                        <span id="quickview-old-price"
                                                            class="ft-medium text-muted line-through fs-md mr-2"></span>
                                                        <span id="quickview-new-price"
                                                            class="ft-bold theme-cl fs-lg mr-2"></span>
                                                        <span id="quickview-stock-status"
                                                            class="ft-regular text-danger bg-light-danger py-1 px-2 fs-sm"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="prt_03 mb-3">
                                                <p id="quickview-description"></p>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                        </div>
                    </div>





    </section>
@endsection

