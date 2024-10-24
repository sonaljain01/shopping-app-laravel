@extends('front.layouts.app')

@section('content')
<section class="middle">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <div class="col-md-6">
                    @if (count($product->product_images) > 0)
                        <img src="{{ asset($product->product_images[0]->image) }}" alt="{{ $product->title }}" class="img-fluid" />
                    @endif
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <div class="prd_details">
                    <div class="prt_01 mb-1">
                        <span class="text-purple bg-light-purple rounded py-1">{{ $product->category->name }}</span>
                    </div>
                    <div class="prt_02 mb-3">
                        <h2 class="ft-bold mb-1">{{ $product->title }}</h2>
                        
                        <div class="text-left">
                            <div class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="fas fa-star {{ $i < $product->rating ? 'filled' : '' }}"></i>
                                @endfor
                                <span class="small">({{ $product->reviews_count }} Reviews)</span>
                            </div>
                            <div class="elis_rty">
                                <span class="ft-medium text-muted line-through fs-md mr-2">${{ $product->price }}</span>
                                <span class="ft-bold theme-cl fs-lg mr-2">${{ $product->compare_price }}</span>
                                <span class="ft-regular text-light bg-success py-1 px-2 fs-sm">In Stock</span>
                            </div>
                        </div>
                    </div>

                    <div class="prt_03 mb-4">
                        <p>{{ $product->description }}</p>
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
                            <div class="form-check form-option size-option  form-check-inline mb-2">
                                <input class="form-check-input" type="radio" name="size" id="42">
                                <label class="form-option-label" for="42">42</label>
                            </div>
                            <div class="form-check form-option size-option  form-check-inline mb-2">
                                <input class="form-check-input" type="radio" name="size" id="44">
                                <label class="form-option-label" for="44">44</label>
                            </div>
                            <div class="form-check form-option size-option  form-check-inline mb-2">
                                <input class="form-check-input" type="radio" name="size" id="46">
                                <label class="form-option-label" for="46">46</label>
                            </div>
                            <div class="form-check form-option size-option  form-check-inline mb-2">
                                <input class="form-check-input" type="radio" name="size" id="48">
                                <label class="form-option-label" for="48">48</label>
                            </div>
                            <div class="form-check form-option size-option  form-check-inline mb-2">
                                <input class="form-check-input" type="radio" name="size" id="50">
                                <label class="form-option-label" for="50">50</label>
                            </div>
                        </div>
                    </div>

                    <div class="prt_05 mb-4">
                        <div class="form-row mb-7">
                            <div class="col-12 col-lg-auto">
                                <!-- Quantity -->
                                <select class="mb-2 custom-select">
                                  @for ($i = 1; $i <= 5; $i++)
                                      <option value="{{ $i }}">{{ $i }}</option>
                                  @endfor
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

                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================= Product Detail End ======================== -->

<!-- ======================= Product Description ======================= -->
<section class="middle">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-xl-11 col-lg-12 col-md-12 col-sm-12">
                <ul class="nav nav-tabs b-0 d-flex align-items-center justify-content-center simple_tab_links mb-4" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="description-tab" href="#description" data-toggle="tab" role="tab" aria-controls="description" aria-selected="true">Description</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#information" id="information-tab" data-toggle="tab" role="tab" aria-controls="information" aria-selected="false">Additional information</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#reviews" id="reviews-tab" data-toggle="tab" role="tab" aria-controls="reviews" aria-selected="false">Reviews</a>
                    </li>
                </ul>
                
                <div class="tab-content" id="myTabContent">
                    
                    <!-- Description Content -->
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                        <div class="description_info">
                            <p class="p-0 mb-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                            <p class="p-0">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga.</p>
                        </div>
                    </div>
                    
                    <!-- Additional Content -->
                    <div class="tab-pane fade" id="information" role="tabpanel" aria-labelledby="information-tab">
                        <div class="additionals">
                            <table class="table">
                                <tbody>
                                    <tr>
                                      <th class="ft-medium text-dark">ID</th>
                                      <td>#1253458</td>
                                    </tr>
                                    <tr>
                                      <th class="ft-medium text-dark">SKU</th>
                                      <td>KUM125896</td>
                                    </tr>
                                    <tr>
                                      <th class="ft-medium text-dark">Color</th>
                                      <td>Sky Blue</td>
                                    </tr>
                                    <tr>
                                      <th class="ft-medium text-dark">Size</th>
                                      <td>Xl, 42</td>
                                    </tr>
                                    <tr>
                                      <th class="ft-medium text-dark">Weight</th>
                                      <td>450 Gr</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Reviews Content -->
                    <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                        <div class="reviews_info">
                            <div class="single_rev d-flex align-items-start br-bottom py-3">
                                <div class="single_rev_thumb"><img src="assets/img/team-1.jpg" class="img-fluid circle" width="90" alt="" /></div>
                                <div class="single_rev_caption d-flex align-items-start pl-3">
                                    <div class="single_capt_left">
                                        <h5 class="mb-0 fs-md ft-medium lh-1">Daniel Rajdesh</h5>
                                        <span class="small">30 jul 2021</span>
                                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum</p>
                                    </div>
                                    <div class="single_capt_right">
                                        <div class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Single Review -->
                            <div class="single_rev d-flex align-items-start br-bottom py-3">
                                <div class="single_rev_thumb"><img src="assets/img/team-2.jpg" class="img-fluid circle" width="90" alt="" /></div>
                                <div class="single_rev_caption d-flex align-items-start pl-3">
                                    <div class="single_capt_left">
                                        <h5 class="mb-0 fs-md ft-medium lh-1">Seema Gupta</h5>
                                        <span class="small">30 Aug 2021</span>
                                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum</p>
                                    </div>
                                    <div class="single_capt_right">
                                        <div class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Single Review -->
                            <div class="single_rev d-flex align-items-start br-bottom py-3">
                                <div class="single_rev_thumb"><img src="assets/img/team-3.jpg" class="img-fluid circle" width="90" alt="" /></div>
                                <div class="single_rev_caption d-flex align-items-start pl-3">
                                    <div class="single_capt_left">
                                        <h5 class="mb-0 fs-md ft-medium lh-1">Mark Jugermi</h5>
                                        <span class="small">10 Oct 2021</span>
                                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum</p>
                                    </div>
                                    <div class="single_capt_right">
                                        <div class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Single Review -->
                            <div class="single_rev d-flex align-items-start py-3">
                                <div class="single_rev_thumb"><img src="assets/img/team-4.jpg" class="img-fluid circle" width="90" alt="" /></div>
                                <div class="single_rev_caption d-flex align-items-start pl-3">
                                    <div class="single_capt_left">
                                        <h5 class="mb-0 fs-md ft-medium lh-1">Meena Rajpoot</h5>
                                        <span class="small">17 Dec 2021</span>
                                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum</p>
                                    </div>
                                    <div class="single_capt_right">
                                        <div class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="reviews_rate">
                            <form class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <h4>Submit Rating</h4>
                                </div>
                                
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="revie_stars d-flex align-items-center justify-content-between px-2 py-2 gray rounded mb-2 mt-1">
                                        <div class="srt_013">
                                            <div class="submit-rating">
                                              <input id="star-5" type="radio" name="rating" value="star-5" />
                                              <label for="star-5" title="5 stars">
                                                <i class="active fa fa-star" aria-hidden="true"></i>
                                              </label>
                                              <input id="star-4" type="radio" name="rating" value="star-4" />
                                              <label for="star-4" title="4 stars">
                                                <i class="active fa fa-star" aria-hidden="true"></i>
                                              </label>
                                              <input id="star-3" type="radio" name="rating" value="star-3" />
                                              <label for="star-3" title="3 stars">
                                                <i class="active fa fa-star" aria-hidden="true"></i>
                                              </label>
                                              <input id="star-2" type="radio" name="rating" value="star-2" />
                                              <label for="star-2" title="2 stars">
                                                <i class="active fa fa-star" aria-hidden="true"></i>
                                              </label>
                                              <input id="star-1" type="radio" name="rating" value="star-1" />
                                              <label for="star-1" title="1 star">
                                                <i class="active fa fa-star" aria-hidden="true"></i>
                                              </label>
                                            </div>
                                        </div>
                                        
                                        <div class="srt_014">
                                            <h6 class="mb-0">4 Star</h6>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="medium text-dark ft-medium">Full Name</label>
                                        <input type="text" class="form-control" />
                                    </div>
                                </div>
                                
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="medium text-dark ft-medium">Email Address</label>
                                        <input type="email" class="form-control" />
                                    </div>
                                </div>
                                
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="medium text-dark ft-medium">Description</label>
                                        <textarea class="form-control"></textarea>
                                    </div>
                                </div>
                                
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group m-0">
                                        <a class="btn btn-white stretched-link hover-black">Submit Review <i class="lni lni-arrow-right"></i></a>
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ======================= Product Description End ==================== -->

<!-- ======================= Similar Products Start ============================ -->
<section class="middle pt-0">
    <div class="container">
        
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="sec_title position-relative text-center">
                    <h2 class="off_title">Similar Products</h2>
                    <h3 class="ft-bold pt-3">Matching Producta</h3>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="slide_items">
                    
                    <!-- single Item -->
                    <div class="single_itesm">
                        <div class="product_grid card b-0 mb-0">
                            <div class="badge bg-success text-white position-absolute ft-regular ab-left text-upper">Sale</div>
                            <button class="snackbar-wishlist btn btn_love t"><i class="far fa-heart"></i></button> 
                            <div class="card-body">
                                <div class="shop_thumb position-relative">
                                    <a class="card-img-top d-block overflow-hidden" href="shop-single-v1.html"><img class="card-img-top" src="assets/img/product/16.png" alt="..."></a>
                                    <div class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                        <div class="edlio"><a href="#" data-toggle="modal" data-target="#quickview" class="text-white fs-sm ft-medium"><i class="fas fa-eye mr-1"></i>Quick View</a></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer b-0 p-3 pb-0 d-flex align-items-start justify-content-center">
                                <div class="text-left">
                                    <div class="text-center">
                                        <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1"><a href="shop-single-v1.html">Half Running Set</a></h5>
                                        <div class="elis_rty"><span class="ft-bold fs-md text-dark">$119.00</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- single Item -->
                    <div class="single_itesm">
                        <div class="product_grid card b-0 mb-0">
                            <div class="badge bg-info text-white position-absolute ft-regular ab-left text-upper">New</div>
                            <button class="snackbar-wishlist btn btn_love position-absolute ab-right"><i class="far fa-heart"></i></button> 
                            <div class="card-body p-0">
                                <div class="shop_thumb position-relative">
                                    <a class="card-img-top d-block overflow-hidden" href="shop-single-v1.html"><img class="card-img-top" src="assets/img/product/17.png" alt="..."></a>
                                    <div class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                        <div class="edlio"><a href="#" data-toggle="modal" data-target="#quickview" class="text-white fs-sm ft-medium"><i class="fas fa-eye mr-1"></i>Quick View</a></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer b-0 p-3 pb-0 d-flex align-items-start justify-content-center">
                                <div class="text-left">
                                    <div class="text-center">
                                        <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1"><a href="shop-single-v1.html">Formal Men Lowers</a></h5>
                                        <div class="elis_rty"><span class="text-muted ft-medium line-through mr-2">$129.00</span><span class="ft-bold theme-cl fs-md">$79.00</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- single Item -->
                    <div class="single_itesm">
                        <div class="product_grid card b-0 mb-0">
                            <button class="snackbar-wishlist btn btn_love position-absolute ab-right"><i class="far fa-heart"></i></button> 
                            <div class="card-body p-0">
                                <div class="shop_thumb position-relative">
                                    <a class="card-img-top d-block overflow-hidden" href="shop-single-v1.html"><img class="card-img-top" src="assets/img/product/18.png" alt="..."></a>
                                    <div class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                        <div class="edlio"><a href="#" data-toggle="modal" data-target="#quickview" class="text-white fs-sm ft-medium"><i class="fas fa-eye mr-1"></i>Quick View</a></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer b-0 p-3 pb-0 d-flex align-items-start justify-content-center">
                                <div class="text-left">
                                    <div class="text-center">
                                        <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1"><a href="shop-single-v1.html">Half Running Suit</a></h5>
                                        <div class="elis_rty"><span class="ft-bold fs-md text-dark">$80.00</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- single Item -->
                    <div class="single_itesm">
                        <div class="product_grid card b-0 mb-0">
                            <div class="badge bg-warning text-white position-absolute ft-regular ab-left text-upper">Hot</div>
                            <button class="snackbar-wishlist btn btn_love position-absolute ab-right"><i class="far fa-heart"></i></button> 
                            <div class="card-body p-0">
                                <div class="shop_thumb position-relative">
                                    <a class="card-img-top d-block overflow-hidden" href="shop-single-v1.html"><img class="card-img-top" src="assets/img/product/19.png" alt="..."></a>
                                    <div class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                        <div class="edlio"><a href="#" data-toggle="modal" data-target="#quickview" class="text-white fs-sm ft-medium"><i class="fas fa-eye mr-1"></i>Quick View</a></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer b-0 p-3 pb-0 d-flex align-items-start justify-content-center">
                                <div class="text-left">
                                    <div class="text-center">
                                        <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1"><a href="shop-single-v1.html">Half Fancy Lady Dress</a></h5>
                                        <div class="elis_rty"><span class="text-muted ft-medium line-through mr-2">$149.00</span><span class="ft-bold theme-cl fs-md">$110.00</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- single Item -->
                    <div class="single_itesm">
                        <div class="product_grid card b-0 mb-0">
                            <button class="snackbar-wishlist btn btn_love position-absolute ab-right"><i class="far fa-heart"></i></button> 
                            <div class="card-body p-0">
                                <div class="shop_thumb position-relative">
                                    <a class="card-img-top d-block overflow-hidden" href="shop-single-v1.html"><img class="card-img-top" src="assets/img/product/20.png" alt="..."></a>
                                    <div class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                        <div class="edlio"><a href="#" data-toggle="modal" data-target="#quickview" class="text-white fs-sm ft-medium"><i class="fas fa-eye mr-1"></i>Quick View</a></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer b-0 p-3 pb-0 d-flex align-items-start justify-content-center">
                                <div class="text-left">
                                    <div class="text-center">
                                        <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1"><a href="shop-single-v1.html">Flix Flox Jeans</a></h5>
                                        <div class="elis_rty"><span class="text-muted ft-medium line-through mr-2">$90.00</span><span class="ft-bold theme-cl fs-md">$49.00</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- single Item -->
                    <div class="single_itesm">
                        <div class="product_grid card b-0 mb-0">
                            <div class="badge bg-danger text-white position-absolute ft-regular ab-left text-upper">Hot</div>
                            <button class="snackbar-wishlist btn btn_love position-absolute ab-right"><i class="far fa-heart"></i></button> 
                            <div class="card-body p-0">
                                <div class="shop_thumb position-relative">
                                    <a class="card-img-top d-block overflow-hidden" href="shop-single-v1.html"><img class="card-img-top" src="assets/img/product/21.png" alt="..."></a>
                                    <div class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                        <div class="edlio"><a href="#" data-toggle="modal" data-target="#quickview" class="text-white fs-sm ft-medium"><i class="fas fa-eye mr-1"></i>Quick View</a></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer b-0 p-3 pb-0 d-flex align-items-start justify-content-center">
                                <div class="text-left">
                                    <div class="text-center">
                                        <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1"><a href="shop-single-v1.html">Fancy Salwar Suits</a></h5>
                                        <div class="elis_rty"><span class="ft-bold fs-md text-dark">$114.00</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- single Item -->
                    <div class="single_itesm">
                        <div class="product_grid card b-0 mb-0">
                            <div class="badge bg-success text-white position-absolute ft-regular ab-left text-upper">Sale</div>
                            <button class="snackbar-wishlist btn btn_love position-absolute ab-right"><i class="far fa-heart"></i></button> 
                            <div class="card-body p-0">
                                <div class="shop_thumb position-relative">
                                    <a class="card-img-top d-block overflow-hidden" href="shop-single-v1.html"><img class="card-img-top" src="assets/img/product/22.png" alt="..."></a>
                                    <div class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                        <div class="edlio"><a href="#" data-toggle="modal" data-target="#quickview" class="text-white fs-sm ft-medium"><i class="fas fa-eye mr-1"></i>Quick View</a></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer b-0 p-3 pb-0 d-flex align-items-start justify-content-center">
                                <div class="text-left">
                                    <div class="text-center">
                                        <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1"><a href="shop-single-v1.html">Collot Full Dress</a></h5>
                                        <div class="elis_rty"><span class="ft-bold theme-cl fs-md text-dark">$120.00</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
    </div>
</section>
@endsection