{{-- @foreach ($orders as $order)
    <div>
        <h3>Order #{{ $order->id }} - {{ $order->status }}</h3>
        <p>Total: {{ $order->total_amount }}</p>
        <h4>Items:</h4>
        <ul>
            @foreach ($order->orderItems as $item)
                <li>{{ $item->product->name }} ({{ $item->quantity }})</li>
            @endforeach
        </ul>
    </div>
@endforeach --}}

@extends('front.layouts.app')

@section('content')
    <div class="container">
        <div class="row align-items-start justify-content-between">

            <div class="col-12 col-md-12 col-lg-4 col-xl-4 text-center miliods">
                <div class="d-block border rounded">
                    <div class="dashboard_author px-2 py-5">
                        <div class="dash_auth_thumb circle p-1 border d-inline-flex mx-auto mb-2">
                            <img src="assets/img/team-1.jpg" class="img-fluid circle" width="100" alt="" />
                        </div>
                        <div class="dash_caption">
                            <h4 class="fs-md ft-medium mb-0 lh-1">Adam Wishnoi</h4>
                            <span class="text-muted smalls">Australia</span>
                        </div>
                    </div>

                    <div class="dashboard_author">
                        <h4 class="px-3 py-2 mb-0 lh-2 gray fs-sm ft-medium text-muted text-uppercase text-left">Dashboard
                            Navigation</h4>
                        <ul class="dahs_navbar">
                            <li><a href="my-orders.html" class="active"><i class="lni lni-shopping-basket mr-2"></i>My
                                    Order</a></li>
                            <li><a href="wishlist.html"><i class="lni lni-heart mr-2"></i>Wishlist</a></li>
                            <li><a href="profile-info.html"><i class="lni lni-user mr-2"></i>Profile Info</a></li>
                            <li><a href="addresses.html"><i class="lni lni-map-marker mr-2"></i>Addresses</a></li>
                            <li><a href="payment-methode.html"><i class="lni lni-mastercard mr-2"></i>Payment Methode</a>
                            </li>
                            <li><a href="login.html"><i class="lni lni-power-switch mr-2"></i>Log Out</a></li>
                        </ul>
                    </div>

                </div>
            </div>

            <div class="col-12 col-md-12 col-lg-8 col-xl-8 text-center">

                <!-- Single Order List -->
                {{-- <div class="ord_list_wrap border mb-4 mfliud">
                <div class="ord_list_head gray d-flex align-items-center justify-content-between px-3 py-3">
                    <div class="olh_flex">
                        <p class="m-0 p-0"><span class="text-muted">Order Number</span></p>
                        <h6 class="mb-0 ft-medium">#{{ $order->id }}</h6>
                    </div>	
                    <div class="olh_flex">
                        <a href="javascript:void(0);" class="btn btn-sm btn-dark">Track Order</a>
                    </div>	
                </div>
                <div class="ord_list_body text-left">
                    <!-- First Product -->
                    <div class="row align-items-center justify-content-center m-0 py-4 br-bottom">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-12">
                            <div class="cart_single d-flex align-items-start mfliud-bot">
                                <div class="cart_selected_single_thumb">
                                    <a href="#"><img src="assets/img/product/4.jpg" width="75" class="img-fluid rounded" alt=""></a>
                                </div>
                                <div class="cart_single_caption pl-3">
                                    <p class="mb-0"><span class="text-muted small">Dresses</span></p>
                                    <h4 class="product_title fs-sm ft-medium mb-1 lh-1">Women Striped Shirt Dress</h4>
                                    <p class="mb-2"><span class="text-dark medium">Size: 36</span>, <span class="text-dark medium">Color: Red</span></p>
                                    <h4 class="fs-sm ft-bold mb-0 lh-1">$129</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-6">
                            <p class="mb-1 p-0"><span class="text-muted">Status</span></p>
                            <div class="delv_status"><span class="ft-medium small text-warning bg-light-warning rounded px-3 py-1">In Progress</span></div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-6">
                            <p class="mb-1 p-0"><span class="text-muted">Expected date by:</span></p>
                            <h6 class="mb-0 ft-medium fs-sm">22 September 2021</h6>
                        </div>
                    </div>
                    
                </div>
            </div>     --}}
                @foreach ($orders as $order)
                    <div class="ord_list_wrap border mb-4 mfliud">
                        <div class="ord_list_head gray d-flex align-items-center justify-content-between px-3 py-3">
                            <div class="olh_flex">
                                <p class="m-0 p-0"><span class="text-muted">Order Number</span></p>
                                <h6 class="mb-0 ft-medium">#{{ $order->id }}</h6>
                            </div>
                            <div class="olh_flex">
                                <a href="javascript:void(0);" class="btn btn-sm btn-dark">Track Order</a>
                            </div>
                        </div>
                        <div class="ord_list_body text-left">
                            @foreach ($order->orderItems as $item)
                                <!-- Loop through order items -->
                                <div class="row align-items-center justify-content-center m-0 py-4 br-bottom">
                                    <div class="col-xl-5 col-lg-5 col-md-5 col-12">
                                        <div class="cart_single d-flex align-items-start mfliud-bot">
                                            <div class="cart_selected_single_thumb">
                                                <a href="#"><img src="{{ $item->product->image }}" width="75"
                                                        class="img-fluid rounded" alt=""></a>
                                            </div>
                                            <div class="cart_single_caption pl-3">
                                                <p class="mb-0"><span
                                                        class="text-muted small">{{ $item->product->category->name }}</span>
                                                </p>
                                                <h4 class="product_title fs-sm ft-medium mb-1 lh-1">
                                                    {{ $item->product->title }}</h4>
                                                
                                                <h4 class="fs-sm ft-bold mb-0 lh-1">${{ $item->price }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-6">
                                        <p class="mb-1 p-0"><span class="text-muted">Status</span></p>
                                        <div class="delv_status"><span
                                                class="ft-medium small text-warning bg-light-warning rounded px-3 py-1">In
                                                Progress</span></div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-6">
                                        <p class="mb-1 p-0"><span class="text-muted">Expected date by:</span></p>
                                        <h6 class="mb-0 ft-medium fs-sm">{{ $order->expected_delivery_date }}</h6>
                                        <!-- Adjust this field accordingly -->
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach


                <!-- End Order List -->


            </div>

        </div>
    </div>
@endsection
