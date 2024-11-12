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
    <div class="gray py-3">
        <div class="container">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">My Order</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row align-items-start justify-content-between">
            @include('front.layouts.sidebar_two')


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
                                <a href="{{ route('track.orders.form') }}" class="btn btn-sm btn-dark">Track Order</a>
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
                                                class="ft-medium small text-warning bg-light-warning rounded px-3 py-1">{{ $order->status }}</span></div>
                                        </div>
                                        {{-- <div class="delv_status">
                                            <span class="ft-medium small rounded px-3 py-1 
                                            {{ $order->status == 'cancelled' ? 'text-danger bg-light-danger'
                                                : ($order->status == 'completed'
                                                    ? 'text-success bg-light-success'
                                                    : 'text-warning bg-light-warning') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div> --}}

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
