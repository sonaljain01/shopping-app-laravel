@extends('front.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center justify-content-between">
    
        <div class="col-12 col-md-12 col-lg-4 col-xl-4 text-center miliods">
            <div class="d-block border rounded mfliud-bot">
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
                    <h4 class="px-3 py-2 mb-0 lh-2 gray fs-sm ft-medium text-muted text-uppercase text-left">Dashboard Navigation</h4>
                    <ul class="dahs_navbar">
                        <li><a href="my-orders.html"><i class="lni lni-shopping-basket mr-2"></i>My Order</a></li>
                        <li><a href="wishlist.html" class="active"><i class="lni lni-heart mr-2"></i>Wishlist</a></li>
                        <li><a href="profile-info.html"><i class="lni lni-user mr-2"></i>Profile Info</a></li>
                        <li><a href="addresses.html"><i class="lni lni-map-marker mr-2"></i>Addresses</a></li>
                        <li><a href="payment-methode.html"><i class="lni lni-mastercard mr-2"></i>Payment Methode</a></li>
                        <li><a href="login.html"><i class="lni lni-power-switch mr-2"></i>Log Out</a></li>
                    </ul>
                </div>
                
            </div>
        </div>
        
        <div class="col-12 col-md-12 col-lg-8 col-xl-8 text-center">
            <!-- row -->
            <div class="row align-items-center">
            
                <!-- Single -->
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                    <div class="product_grid card b-0">
                        <div class="badge bg-success text-white position-absolute ft-regular ab-left text-upper">Sale</div>
                        <button class="btn btn_love position-absolute ab-right theme-cl"><i class="fas fa-times"></i></button> 
                        <div class="card-body p-0">
                            <div class="shop_thumb position-relative">
                                <a class="card-img-top d-block overflow-hidden" href="shop-single-v1.html"><img class="card-img-top" src="assets/img/product/1.jpg" alt="..."></a>
                                <div class="product-hover-overlay bg-dark d-flex align-items-center justify-content-center">
                                    <div class="edlio"><a href="#" data-toggle="modal" data-target="#quickview" class="text-white fs-sm ft-medium"><i class="fas fa-eye mr-1"></i>Quick View</a></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footers b-0 pt-3 px-2 bg-white d-flex align-items-start justify-content-center">
                            <div class="text-left">
                                <div class="text-center">
                                    <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1"><a href="shop-single-v1.html">Half Running Set</a></h5>
                                    <div class="elis_rty"><span class="ft-bold fs-md text-dark">$119.00</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
              

             
                
                
                
            </div>
            <!-- row -->
        </div>
        
    </div>
</div>
@endsection