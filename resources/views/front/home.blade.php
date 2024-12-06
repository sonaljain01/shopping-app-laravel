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
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
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
                <div class="col-xl-12 col-lg-12 col-md-12 text-center">
                    <a href="#" class="btn stretched-link borders m-auto"><i class="lni lni-reload mr-2"></i>Load
                        More</a>
                </div>
            </div>
        </div>
    </section>
@endsection
