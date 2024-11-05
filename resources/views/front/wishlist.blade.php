@extends('front.layouts.app')

@section('content')
<div class="gray py-3">
    <div class="container">
        <div class="row">
            <div class="colxl-12 col-lg-12 col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('front.shop') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
    <div class="container">
        
        <div class="row justify-content-center justify-content-between">
            @include('front.layouts.sidebar_two')

            <div class="col-12 col-md-12 col-lg-8 col-xl-8 text-center">
                <h4>Your Wishlist ({{ $wishlistCount }})</h4> <!-- Display the wishlist count -->

                <div class="row align-items-center">
                    <div class="row">
                        @foreach ($wishlists as $wishlist)
                            @if ($wishlist->product)
                                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                                    <div class="product_grid card b-0">
                                        <form action="{{ route('wishlist.remove', $wishlist->id) }}" method="POST" class="position-absolute ab-right theme-cl">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn_love"><i class="fas fa-times"></i></button>
                                        </form>
                                        <div class="card-body p-0">
                                            <div class="shop_thumb position-relative">
                                                <a class="card-img-top d-block overflow-hidden" href="{{ route('product.show', $wishlist->product->slug) }}">
                                                    <img class="card-img-top" src="{{ asset($wishlist->product->product_images->first()->image ?? 'front-asset/img/path-to-placeholder-image/placeholder.jpg') }}" alt="{{ $wishlist->product->title }}">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-footers b-0 pt-3 px-2 bg-white d-flex align-items-start justify-content-center">
                                            <div class="text-center">
                                                <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1">
                                                    <a href="{{ route('product.show', $wishlist->product->slug) }}">{{ $wishlist->product->title }}</a>
                                                </h5>
                                                <div class="elis_rty">
                                                    <span class="ft-bold fs-md text-dark">Rs.{{ $wishlist->product->price }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            @if (session('alert'))
                swal({
                    title: "Warning!",
                    text: "{{ session('alert') }}",
                    type: "warning",
                    confirmButtonText: "Okay"
                });
            @endif
        });
    </script>
@endsection
