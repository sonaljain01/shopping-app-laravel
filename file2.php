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



                <div
                                                class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                                <div class="edlio">
                                                    <a href="#" data-toggle="modal" data-target="#quickview"
                                                        class="text-white fs-sm ft-medium">
                                                        <i class="fas fa-eye mr-1"></i>Quick View
                                                    </a>
                                                </div>
                                            </div>

                <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                    <div class="product_grid card b-0">
                        <div class="badge bg-info text-white position-absolute ft-regular ab-left text-upper">New</div>
                        <div class="card-body p-0">
                            <div class="shop_thumb position-relative">
                                <a class="card-img-top d-block overflow-hidden" href="shop-single-v1.html"><img
                                        class="card-img-top" src="assets/img/product/12.jpg" alt="..."></a>
                                <div class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                    <div class="edlio"><a href="#" data-toggle="modal" data-target="#quickview"
                                            class="text-white fs-sm ft-medium"><i class="fas fa-eye mr-1"></i>Quick View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer b-0 p-0 pt-2 bg-white">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="text-left">
                                    <div class="form-check form-option form-check-inline mb-1">
                                        <input class="form-check-input" type="radio" name="color2" id="white2">
                                        <label class="form-option-label small rounded-circle" for="white2"><span
                                                class="form-option-color rounded-circle blc5"></span></label>
                                    </div>
                                    <div class="form-check form-option form-check-inline mb-1">
                                        <input class="form-check-input" type="radio" name="color2" id="blue2">
                                        <label class="form-option-label small rounded-circle" for="blue2"><span
                                                class="form-option-color rounded-circle blc2"></span></label>
                                    </div>
                                    <div class="form-check form-option form-check-inline mb-1">
                                        <input class="form-check-input" type="radio" name="color2" id="yellow2">
                                        <label class="form-option-label small rounded-circle" for="yellow2"><span
                                                class="form-option-color rounded-circle blc6"></span></label>
                                    </div>
                                    <div class="form-check form-option form-check-inline mb-1">
                                        <input class="form-check-input" type="radio" name="color2" id="pink2">
                                        <label class="form-option-label small rounded-circle" for="pink2"><span
                                                class="form-option-color rounded-circle blc4"></span></label>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button class="btn auto btn_love snackbar-wishlist"><i
                                            class="far fa-heart"></i></button>
                                </div>
                            </div>
                            <div class="text-left">
                                
                                    @if ($products->count() > 0)
                                    <div class="row">
                                        @foreach ($products as $product)
                                            <div class="col-md-4 col-sm-6 mb-4">
                                                <div class="card product-card">
                                                    <!-- Product Images -->
                                                    @if (count($product->product_images) > 0)
                                                        <img class="card-img-top"
                                                            src="{{ asset($product->product_images[0]->image) }}" />
                                                    @endif
                                
                                                    <!-- Product Details -->
                                                    <div class="card-body">
                                                        <h5 class="card-title fw-bolder fs-md mb-2">
                                                            <a href="{{ route('product.show', $product->id) }}">{{ $product->title }}</a>
                                                        </h5>
                                                        <div class="elis_rty">
                                                            <span class="ft-bold text-dark fs-sm">${{ $product->price }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p>No products available</p>
                                @endif