@extends('front.layouts.app')

@section('content')
    <div class="gray py-3">
        <div class="container">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Library</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div id = "main-wrapper">
        <section class="middle">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        <div class="col-md-6">
                            @if (count($product->product_images) > 0)
                                <img src="{{ asset($product->product_images[0]->image) }}" alt="{{ $product->title }}"
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
                                    <div class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
                                        @for ($i = 0; $i < 5; $i++)
                                            <i class="fas fa-star {{ $i < $product->rating ? 'filled' : '' }}"></i>
                                        @endfor
                                        <span class="small">({{ $product->reviews_count }} Reviews)</span>
                                    </div>
                                    <div class="elis_rty">
                                        <span
                                            class="ft-medium text-muted line-through fs-md mr-2">{{ $product->currency }}${{ $product->compare_price }}</span>
                                        <span class="ft-bold theme-cl fs-lg mr-2">{{ $product->currency }}{{ $product->price }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="prt_03 mb-4">
                                {!! $product->description !!}
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
                                @foreach ($product->attributes->groupBy('name') as $attributeName => $attributes)
                                    <div class="attribute-item mb-2">
                                        <strong>{{ $attributeName }}:</strong>
                                        <span>
                                            @php
                                                $values = [];
                                            @endphp
                                            @foreach ($attributes as $attribute)
                                                @php
                                                    // Getting the attribute value from the pivot
                                                    $value = $attribute->values->firstWhere(
                                                        'id',
                                                        $attribute->pivot->attribute_value_id,
                                                    );
                                                    if ($value) {
                                                        $values[] = $value->value;
                                                    }
                                                @endphp
                                            @endforeach

                                            @if (count($values) > 0)
                                                @foreach ($values as $value)
                                                    <span class="badge badge-info">{{ $value }}</span>
                                                @endforeach
                                            @else
                                                <span class="badge badge-secondary">N/A</span>
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
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


                            <div class="prt_04 mb-4">
                                <div class="form-group">
                                    <label for="zip">Check Availability:</label>
                                    <input type="text" id="zip" class="form-control"
                                        placeholder="Enter your pincode">
                                    <div id="delivery-status" class="mt-2"></div>
                                    <input type="hidden" id="city">
                                    <input type="hidden" id="state">
                                </div>
                            </div>

                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    const zipInput = document.getElementById('zip');
                                    const deliveryStatus = document.getElementById('delivery-status');

                                    if (!deliveryStatus) {
                                        console.error("Delivery status element not found!");
                                        return;
                                    }

                                    // Trigger check as soon as the user enters a pincode
                                    zipInput.addEventListener('input', function() {
                                        const zip = zipInput.value.trim();

                                        if (zip) {
                                            fetch(`https://api.postalpincode.in/pincode/${zip}`)
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data[0].Status === 'Success') {
                                                        const city = data[0].PostOffice[0].Division;
                                                        const state = data[0].PostOffice[0].State;

                                                        const cityInput = document.getElementById('city');
                                                        const stateInput = document.getElementById('state');

                                                        if (cityInput && stateInput) {
                                                            cityInput.value = city;
                                                            stateInput.value = state;

                                                            checkDeliveryAvailability(city, state);
                                                        } else {
                                                            console.error('City or State input field not found.');
                                                        }
                                                    } else {
                                                        displayMessage("Invalid pincode or no delivery available.");
                                                    }
                                                })
                                                .catch(error => {
                                                    console.error('Error fetching zip code data:', error);
                                                    displayMessage("Error checking delivery availability.");
                                                });
                                        } else {
                                            displayMessage("Please enter a valid zip code.");
                                        }
                                    });

                                    function checkDeliveryAvailability(city, state) {
                                        const routeTemplate =
                                            `{{ route('check-delivery', ['city' => '__CITY__', 'state' => '__STATE__']) }}`;
                                        const finalUrl = routeTemplate
                                            .replace('__CITY__', encodeURIComponent(city))
                                            .replace('__STATE__', encodeURIComponent(state));

                                        fetch(finalUrl)
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.delivery_available) {
                                                    displayMessage("Delivery is available to your location.");
                                                    deliveryStatus.classList.remove('text-danger');
                                                    deliveryStatus.classList.add('text-success');
                                                } else {
                                                    displayMessage("Sorry, we do not deliver to your location.");
                                                    deliveryStatus.classList.remove('text-success');
                                                    deliveryStatus.classList.add('text-danger');
                                                }
                                            })
                                            .catch(error => {
                                                console.error("Error checking delivery availability:", error);
                                                displayMessage("Error checking delivery availability.");
                                                deliveryStatus.classList.remove('text-success');
                                                deliveryStatus.classList.add('text-danger');
                                            });
                                    }

                                    function displayMessage(message) {
                                        deliveryStatus.textContent = message;
                                    }
                                });
                            </script>


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
                                        <!-- Submit to Cart -->
                                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-block custom-height bg-dark mb-2">
                                                <i class="lni lni-shopping-basket mr-2"></i>Add to Cart
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-12 col-lg-auto">
                                        <!-- Wishlist -->
                                        <form action="{{ route('wishlist.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit"
                                                class="btn custom-height btn-default btn-block mb-2 text-dark">
                                                <i class="lni lni-heart mr-2"></i>Wishlist
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
        </section>

        <!-- ======================= Product Detail End ======================== -->

        <!-- ======================= Product Description ======================= -->
        <section class="middle">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-xl-11 col-lg-12 col-md-12 col-sm-12">
                        <ul class="nav nav-tabs b-0 d-flex align-items-center justify-content-center simple_tab_links mb-4"
                            id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="description-tab" href="#description" data-toggle="tab"
                                    role="tab" aria-controls="description" aria-selected="true">Description</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" href="#information" id="information-tab" data-toggle="tab"
                                    role="tab" aria-controls="information" aria-selected="false">Additional
                                    information</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" href="#reviews" id="reviews-tab" data-toggle="tab" role="tab"
                                    aria-controls="reviews" aria-selected="false">Reviews</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">

                            <!-- Description Content -->
                            <div class="tab-pane fade show active" id="description" role="tabpanel"
                                aria-labelledby="description-tab">
                                <div class="description_info">
                                    <p class="p-0 mb-2">{!! $product->description !!}</p>
                                    <p class="p-0"></p>
                                </div>
                            </div>

                            <!-- Additional Content -->
                            <div class="tab-pane fade" id="information" role="tabpanel"
                                aria-labelledby="information-tab">
                                <div class="additionals">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th class="ft-medium text-dark">ID</th>
                                                <td>#{{ $product->id }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ft-medium text-dark">SKU</th>
                                                <td>{{ $product->sku }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ft-medium text-dark">Color</th>
                                                <td>{{ $product->color }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ft-medium text-dark">Size</th>
                                                <td>{{ $product->size }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ft-medium text-dark">Weight</th>
                                                <td>{{ $product->weight }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Reviews Content -->
                            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                <div class="reviews_info">
                                    <div class="single_rev d-flex align-items-start br-bottom py-3">
                                        <div class="single_rev_thumb"><img src="assets/img/team-1.jpg"
                                                class="img-fluid circle" width="90" alt="" /></div>
                                        <div class="single_rev_caption d-flex align-items-start pl-3">
                                            <div class="single_capt_left">
                                                <h5 class="mb-0 fs-md ft-medium lh-1">Daniel Rajdesh</h5>
                                                <span class="small">30 jul 2021</span>
                                                <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis
                                                    praesentium voluptatum deleniti atque corrupti quos dolores et quas
                                                    molestias excepturi sint occaecati cupiditate non provident, similique
                                                    sunt in culpa qui officia deserunt mollitia animi, id est laborum</p>
                                            </div>
                                            <div class="single_capt_right">
                                                <div
                                                    class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
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
                                        <div class="single_rev_thumb"><img src="assets/img/team-2.jpg"
                                                class="img-fluid circle" width="90" alt="" /></div>
                                        <div class="single_rev_caption d-flex align-items-start pl-3">
                                            <div class="single_capt_left">
                                                <h5 class="mb-0 fs-md ft-medium lh-1">Seema Gupta</h5>
                                                <span class="small">30 Aug 2021</span>
                                                <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis
                                                    praesentium voluptatum deleniti atque corrupti quos dolores et quas
                                                    molestias excepturi sint occaecati cupiditate non provident, similique
                                                    sunt in culpa qui officia deserunt mollitia animi, id est laborum</p>
                                            </div>
                                            <div class="single_capt_right">
                                                <div
                                                    class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
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
                                        <div class="single_rev_thumb"><img src="assets/img/team-3.jpg"
                                                class="img-fluid circle" width="90" alt="" /></div>
                                        <div class="single_rev_caption d-flex align-items-start pl-3">
                                            <div class="single_capt_left">
                                                <h5 class="mb-0 fs-md ft-medium lh-1">Mark Jugermi</h5>
                                                <span class="small">10 Oct 2021</span>
                                                <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis
                                                    praesentium voluptatum deleniti atque corrupti quos dolores et quas
                                                    molestias excepturi sint occaecati cupiditate non provident, similique
                                                    sunt in culpa qui officia deserunt mollitia animi, id est laborum</p>
                                            </div>
                                            <div class="single_capt_right">
                                                <div
                                                    class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
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
                                        <div class="single_rev_thumb"><img src="assets/img/team-4.jpg"
                                                class="img-fluid circle" width="90" alt="" /></div>
                                        <div class="single_rev_caption d-flex align-items-start pl-3">
                                            <div class="single_capt_left">
                                                <h5 class="mb-0 fs-md ft-medium lh-1">Meena Rajpoot</h5>
                                                <span class="small">17 Dec 2021</span>
                                                <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis
                                                    praesentium voluptatum deleniti atque corrupti quos dolores et quas
                                                    molestias excepturi sint occaecati cupiditate non provident, similique
                                                    sunt in culpa qui officia deserunt mollitia animi, id est laborum</p>
                                            </div>
                                            <div class="single_capt_right">
                                                <div
                                                    class="star-rating align-items-center d-flex justify-content-left mb-1 p-0">
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
                                            <div
                                                class="revie_stars d-flex align-items-center justify-content-between px-2 py-2 gray rounded mb-2 mt-1">
                                                <div class="srt_013">
                                                    <div class="submit-rating">
                                                        <input id="star-5" type="radio" name="rating"
                                                            value="star-5" />
                                                        <label for="star-5" title="5 stars">
                                                            <i class="active fa fa-star" aria-hidden="true"></i>
                                                        </label>
                                                        <input id="star-4" type="radio" name="rating"
                                                            value="star-4" />
                                                        <label for="star-4" title="4 stars">
                                                            <i class="active fa fa-star" aria-hidden="true"></i>
                                                        </label>
                                                        <input id="star-3" type="radio" name="rating"
                                                            value="star-3" />
                                                        <label for="star-3" title="3 stars">
                                                            <i class="active fa fa-star" aria-hidden="true"></i>
                                                        </label>
                                                        <input id="star-2" type="radio" name="rating"
                                                            value="star-2" />
                                                        <label for="star-2" title="2 stars">
                                                            <i class="active fa fa-star" aria-hidden="true"></i>
                                                        </label>
                                                        <input id="star-1" type="radio" name="rating"
                                                            value="star-1" />
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
                                                <a class="btn btn-white stretched-link hover-black">Submit Review <i
                                                        class="lni lni-arrow-right"></i></a>
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
                                    <div
                                        class="badge bg-success text-white position-absolute ft-regular ab-left text-upper">
                                        Sale</div>
                                    <button class="snackbar-wishlist btn btn_love position-absolute ab-right"><i
                                            class="far fa-heart"></i></button>
                                    <div class="card-body p-0">
                                        <div class="shop_thumb position-relative">
                                            <a class="card-img-top d-block overflow-hidden"
                                                href="shop-single-v1.html"><img class="card-img-top"
                                                    src="{{ asset('front-assets/img/product/8.jpg') }}"
                                                    alt="..."></a>
                                            <div
                                                class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                                <div class="edlio"><a href="#" data-toggle="modal"
                                                        data-target="#quickview" class="text-white fs-sm ft-medium"><i
                                                            class="fas fa-eye mr-1"></i>Quick View</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer b-0 p-3 pb-0 d-flex align-items-start justify-content-center">
                                        <div class="text-left">
                                            <div class="text-center">
                                                <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1"><a
                                                        href="shop-single-v1.html">Half Running Set</a></h5>
                                                <div class="elis_rty"><span
                                                        class="ft-bold fs-md text-dark">{{ $product->currency }}119.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- single Item -->
                            <div class="single_itesm">
                                <div class="product_grid card b-0 mb-0">
                                    <div class="badge bg-info text-white position-absolute ft-regular ab-left text-upper">
                                        New</div>
                                    <button class="snackbar-wishlist btn btn_love position-absolute ab-right"><i
                                            class="far fa-heart"></i></button>
                                    <div class="card-body p-0">
                                        <div class="shop_thumb position-relative">
                                            <a class="card-img-top d-block overflow-hidden"
                                                href="shop-single-v1.html"><img class="card-img-top"
                                                    src="{{ asset('front-assets/img/product/9.jpg') }}"
                                                    alt="..."></a>
                                            <div
                                                class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                                <div class="edlio"><a href="#" data-toggle="modal"
                                                        data-target="#quickview" class="text-white fs-sm ft-medium"><i
                                                            class="fas fa-eye mr-1"></i>Quick View</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer b-0 p-3 pb-0 d-flex align-items-start justify-content-center">
                                        <div class="text-left">
                                            <div class="text-center">
                                                <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1"><a
                                                        href="shop-single-v1.html">Formal Men Lowers</a></h5>
                                                <div class="elis_rty"><span
                                                        class="text-muted ft-medium line-through mr-2">{{ $product->currency }}129.00</span><span
                                                        class="ft-bold theme-cl fs-md">{{ $product->currency }}79.00</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- single Item -->
                            <div class="single_itesm">
                                <div class="product_grid card b-0 mb-0">
                                    <button class="snackbar-wishlist btn btn_love position-absolute ab-right"><i
                                            class="far fa-heart"></i></button>
                                    <div class="card-body p-0">
                                        <div class="shop_thumb position-relative">
                                            <a class="card-img-top d-block overflow-hidden"
                                                href="shop-single-v1.html"><img class="card-img-top"
                                                    src="{{ asset('front-assets/img/product/10.jpg') }}"
                                                    alt="..."></a>
                                            <div
                                                class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                                <div class="edlio"><a href="#" data-toggle="modal"
                                                        data-target="#quickview" class="text-white fs-sm ft-medium"><i
                                                            class="fas fa-eye mr-1"></i>Quick View</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer b-0 p-3 pb-0 d-flex align-items-start justify-content-center">
                                        <div class="text-left">
                                            <div class="text-center">
                                                <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1"><a
                                                        href="shop-single-v1.html">Half Running Suit</a></h5>
                                                <div class="elis_rty"><span
                                                        class="ft-bold fs-md text-dark">{{ $product->currency }}80.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- single Item -->
                            <div class="single_itesm">
                                <div class="product_grid card b-0 mb-0">
                                    <div
                                        class="badge bg-warning text-white position-absolute ft-regular ab-left text-upper">
                                        Hot</div>
                                    <button class="snackbar-wishlist btn btn_love position-absolute ab-right"><i
                                            class="far fa-heart"></i></button>
                                    <div class="card-body p-0">
                                        <div class="shop_thumb position-relative">
                                            <a class="card-img-top d-block overflow-hidden"
                                                href="shop-single-v1.html"><img class="card-img-top"
                                                    src="{{ asset('front-assets/img/product/11.jpg') }}"
                                                    alt="..."></a>
                                            <div
                                                class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                                <div class="edlio"><a href="#" data-toggle="modal"
                                                        data-target="#quickview" class="text-white fs-sm ft-medium"><i
                                                            class="fas fa-eye mr-1"></i>Quick View</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer b-0 p-3 pb-0 d-flex align-items-start justify-content-center">
                                        <div class="text-left">
                                            <div class="text-center">
                                                <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1"><a
                                                        href="shop-single-v1.html">Half Fancy Lady Dress</a></h5>
                                                <div class="elis_rty"><span
                                                        class="text-muted ft-medium line-through mr-2">{{ $product->currency }}149.00</span><span
                                                        class="ft-bold theme-cl fs-md">{{ $product->currency }}110.00</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- single Item -->
                            <div class="single_itesm">
                                <div class="product_grid card b-0 mb-0">
                                    <button class="snackbar-wishlist btn btn_love position-absolute ab-right"><i
                                            class="far fa-heart"></i></button>
                                    <div class="card-body p-0">
                                        <div class="shop_thumb position-relative">
                                            <a class="card-img-top d-block overflow-hidden"
                                                href="shop-single-v1.html"><img class="card-img-top"
                                                    src="{{ asset('front-assets/img/product/12.jpg') }}"
                                                    alt="..."></a>
                                            <div
                                                class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                                <div class="edlio"><a href="#" data-toggle="modal"
                                                        data-target="#quickview" class="text-white fs-sm ft-medium"><i
                                                            class="fas fa-eye mr-1"></i>Quick View</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer b-0 p-3 pb-0 d-flex align-items-start justify-content-center">
                                        <div class="text-left">
                                            <div class="text-center">
                                                <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1"><a
                                                        href="shop-single-v1.html">Flix Flox Jeans</a></h5>
                                                <div class="elis_rty"><span
                                                        class="text-muted ft-medium line-through mr-2">{{ $product->currency }}90.00</span><span
                                                        class="ft-bold theme-cl fs-md">{{ $product->currency }}49.00</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- single Item -->
                            <div class="single_itesm">
                                <div class="product_grid card b-0 mb-0">
                                    <div
                                        class="badge bg-danger text-white position-absolute ft-regular ab-left text-upper">
                                        Hot</div>
                                    <button class="snackbar-wishlist btn btn_love position-absolute ab-right"><i
                                            class="far fa-heart"></i></button>
                                    <div class="card-body p-0">
                                        <div class="shop_thumb position-relative">
                                            <a class="card-img-top d-block overflow-hidden"
                                                href="shop-single-v1.html"><img class="card-img-top"
                                                    src="{{ asset('front-assets/img/product/13.jpg') }}"
                                                    alt="..."></a>
                                            <div
                                                class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                                <div class="edlio"><a href="#" data-toggle="modal"
                                                        data-target="#quickview" class="text-white fs-sm ft-medium"><i
                                                            class="fas fa-eye mr-1"></i>Quick View</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer b-0 p-3 pb-0 d-flex align-items-start justify-content-center">
                                        <div class="text-left">
                                            <div class="text-center">
                                                <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1"><a
                                                        href="shop-single-v1.html">Fancy Salwar Suits</a></h5>
                                                <div class="elis_rty"><span
                                                        class="ft-bold fs-md text-dark">{{ $product->currency }}114.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- single Item -->
                            <div class="single_itesm">
                                <div class="product_grid card b-0 mb-0">
                                    <div
                                        class="badge bg-success text-white position-absolute ft-regular ab-left text-upper">
                                        Sale</div>
                                    <button class="snackbar-wishlist btn btn_love position-absolute ab-right"><i
                                            class="far fa-heart"></i></button>
                                    <div class="card-body p-0">
                                        <div class="shop_thumb position-relative">
                                            <a class="card-img-top d-block overflow-hidden"
                                                href="shop-single-v1.html"><img class="card-img-top"
                                                    src="{{ asset('front-assets/img/product/14.jpg') }}"
                                                    alt="..."></a>
                                            <div
                                                class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                                <div class="edlio"><a href="#" data-toggle="modal"
                                                        data-target="#quickview" class="text-white fs-sm ft-medium"><i
                                                            class="fas fa-eye mr-1"></i>Quick View</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer b-0 p-3 pb-0 d-flex align-items-start justify-content-center">
                                        <div class="text-left">
                                            <div class="text-center">
                                                <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1"><a
                                                        href="shop-single-v1.html">Collot Full Dress</a></h5>
                                                <div class="elis_rty"><span
                                                        class="ft-bold theme-cl fs-md text-dark">{{ $product->currency }}120.00</span></div>
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
    </div>
@endsection

@section('customJs')
@endsection
