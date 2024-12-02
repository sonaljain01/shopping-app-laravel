<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta name="author" content="Themezhub" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Kumo- Fashion eCommerce HTML Template</title>

    <!-- Custom CSS -->
    <link href="{{ asset('front-assets/css/styles.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

</head>

<body>

    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader"></div>

    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">

        <!-- ============================================================== -->
        <!-- Top header  -->
        <!-- ============================================================== -->
        <div class="py-2 bg-dark">
            <div class="container">
                <div class="row">

                    <div class="col-xl-4 col-lg-4 col-md-5 col-sm-12 hide-ipad">
                        <div class="top_first"><a href="callto:(+84)0123456789" class="medium text-light">(+84) 0123 456
                                789</a></div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-5 col-sm-12 hide-ipad">
                        <div class="top_second text-center">
                            <p class="medium text-light m-0 p-0">Get Free delivery from $2000 <a href="#"
                                    class="medium text-light text-underline">Shop Now</a></p>
                        </div>
                    </div>

                    <!-- Right Menu -->
                    <div class="col-xl-4 col-lg-4 col-md-5 col-sm-12">

                        <div class="currency-selector dropdown js-dropdown float-right">
                            <a href="javascript:void(0);" data-toggle="dropdown" class="popup-title" title="Currency"
                                aria-label="Currency dropdown">
                                <span class="hidden-xl-down medium text-light">Country:</span>
                                <span class="iso_code medium text-light"> <img
                                        src="{{ asset('vendor/blade-flags/country-' . session('country') . '.svg') }}"
                                        width="20" height="20" />
                                </span>
                                <i class="fa fa-angle-down medium text-light"></i>
                            </a>
                            <ul class="popup-content dropdown-menu">
                                <li><a title="Euro" onclick="country(this.id)" href="javascript:void(0);"
                                        id="in" class="dropdown-item medium text-medium"><img
                                            src="{{ asset('vendor/blade-flags/country-in.svg') }}" width="32"
                                            height="32" />India</a>
                                </li>

                                {{-- <li><a title="Euro" onclick="country(this.id)" href="javascript:void(0);"
                                        id="eu" class="dropdown-item medium text-medium"><img
                                            src="{{ asset('vendor/blade-flags/country-de.svg') }}" width="32"
                                            height="32" />Germany</a>
                                </li> --}}
                                {{-- <li class="current"><a title="US Dollar" href="javascript:void(0);" id="uk"
                                        onclick="country(this.id)" class="dropdown-item medium text-medium">
                                        <img src="{{ asset('vendor/blade-flags/country-gb.svg') }}" width="32"
                                            height="32" />
                                        UK</a></li> --}}
                                <li class="current"><a title="US Dollar"git  href="javascript:void(0);" id="us"
                                        onclick="country(this.id)" class="dropdown-item medium text-medium">
                                        <img src="{{ asset('vendor/blade-flags/country-us.svg') }}" width="32"
                                            height="32" />
                                        USA
                                    </a>
                                </li>
                                <li class="current"><a title="US Dollar" href="javascript:void(0);" id="cn"
                                        onclick="country(this.id)" class="dropdown-item medium text-medium">
                                        <img src="{{ asset('vendor/blade-flags/country-cn.svg') }}" width="32"
                                            height="32" />
                                        China
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Choose Language -->

                        <div class="language-selector-wrapper dropdown js-dropdown float-right mr-3">
                            <a class="popup-title" href="javascript:void(0)" data-toggle="dropdown" title="Language"
                                aria-label="Language dropdown">
                                <span class="hidden-xl-down medium text-light">Language:</span>
                                <span class="iso_code medium text-light">English</span>
                                <i class="fa fa-angle-down medium text-light"></i>
                            </a>
                            <ul class="dropdown-menu popup-content link">
                                <li class="current"><a href="javascript:void(0);"
                                        class="dropdown-item medium text-medium"><img
                                            src="{{ asset('front-assets/img/1.jpg') }}" alt="en" width="16"
                                            height="11" /><span>English</span></a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item medium text-medium"><img
                                            src="{{ asset('front-assets/img/2.jpg') }}" alt="fr" width="16"
                                            height="11" /><span>Français</span></a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item medium text-medium"><img
                                            src="{{ asset('front-assets/img/3.jpg') }}" alt="de" width="16"
                                            height="11" /><span>Deutsch</span></a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item medium text-medium"><img
                                            src="{{ asset('front-assets/img/4.jpg') }}" alt="it"
                                            width="16" height="11" /><span>Italiano</span></a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item medium text-medium"><img
                                            src="{{ asset('front-assets/img/5.jpg') }}" alt="es"
                                            width="16" height="11" /><span>Español</span></a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item medium text-medium"><img
                                            src="{{ asset('front-assets/img/6.jpg') }}" alt="ar"
                                            width="16" height="11" /><span>اللغة العربية</span></a></li>
                            </ul>
                        </div>

                        <div class="currency-selector dropdown js-dropdown float-right mr-3">
                            <a href="javascript:void(0);" class="text-light medium">Wishlist</a>
                        </div>

                        <div class="currency-selector dropdown js-dropdown float-right mr-3">
                            <a href="javascript:void(0);" class="text-light medium">My Account</a>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- Start Navigation -->
        <div class="header header-light dark-text">
            <div class="container">
                <nav id="navigation" class="navigation navigation-landscape">
                    <div class="nav-header">
                        <a class="nav-brand" href="#">
                            <img src="{{ asset('front-assets/img/logo.png') }}" class="logo" alt="" />
                        </a>
                        <div class="nav-toggle"></div>
                        <div class="mobile_nav">
                            <ul>
                                <li>
                                    <a href="#" onclick="openSearch()">
                                        <i class="lni lni-search-alt"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" data-toggle="modal" data-target="#login">
                                        <i class="lni lni-user"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" onclick="openWishlist()">
                                        <i class="lni lni-heart"></i><span class="dn-counter"></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" onclick="openCart()">
                                        <i class="lni lni-shopping-basket"></i><span class="dn-counter">0</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="nav-menus-wrapper" style="transition-property: none;">
                        <ul class="nav-menu">

                            @foreach ($headerMenus as $menu)
                                <li>
                                    <a href="{{ $menu->url }}">{{ $menu->name }}</a>
                                    @if ($menu->children->isNotEmpty())
                                        <ul class="nav-dropdown nav-submenu">
                                            @foreach ($menu->children as $child)
                                                <li>
                                                    <a href="{{ $child->url }}">{{ $child->name }}</a>
                                                    @if ($child->children->isNotEmpty())
                                                        <ul class="nav-dropdown nav-submenu">
                                                            @foreach ($child->children as $grandChild)
                                                                <li>
                                                                    <a
                                                                        href="{{ $grandChild->url }}">{{ $grandChild->name }}</a>
                                                                    @if ($grandChild->children->isNotEmpty())
                                                                        <ul class="nav-dropdown nav-submenu">
                                                                            @foreach ($grandChild->children as $greatGrandChild)
                                                                                <li>
                                                                                    <a
                                                                                        href="{{ $greatGrandChild->url }}">{{ $greatGrandChild->name }}</a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach

                        </ul>

                        <ul class="nav-menu nav-menu-social align-to-right">

                            <li>
                                <a href="#" onclick="openSearch()">
                                    <i class="lni lni-search-alt"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" data-toggle="modal" data-target="#login">
                                    <i class="lni lni-user"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('wishlist.index') }}" onclick="openWishlist()">
                                    <i class="lni lni-heart"></i>
                                    <span class="dn-counter bg-danger">
                                        {{ auth()->check() ? \App\Models\Wishlist::where('user_id', auth()->id())->count() : \App\Models\Wishlist::where('guest_id', session('guest_id'))->count() }}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('cart.index') }}" onclick="openCart()">
                                    <i class="lni lni-shopping-basket"></i>
                                    <span class="dn-counter bg-success">
                                        {{ session('cart') ? array_reduce(session('cart'), fn($total, $item) => $total + $item['quantity'], 0) : '0' }}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>

        </div>
        <script>
            function country(id) {
                console.log("Selected Country ID:", id); // Debug selected ID
                $.ajax({
                    url: "{{ route('update.global.country') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "name": id
                    },
                    success: function(data) {
                        if (data.success) {
                            location.reload(); // Reload to update displayed country
                        } else {
                            alert(data.error || 'Failed to update country.');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseJSON || xhr.responseText); // Log detailed error
                    }
                });
            }
        </script>
        <!-- End Navigation -->
        <div class="clearfix"></div>
        <!-- ============================================================== -->
        <!-- Top header  -->
        <!-- ============================================================== -->

        <!-- ======================= Shop Style 1 ======================== -->

        <!-- ======================= Shop Style 1 ======================== -->


        <!-- ======================= Filter Wrap Style 1 ======================== -->

        <!-- ============================= Filter Wrap ============================== -->

        <!-- ======================= All Product List ======================== -->
        @yield('content')
        <!-- ======================= All Product List ======================== -->

        <!-- ======================= Customer Features ======================== -->
        <section class="px-0 py-3 br-top">
            <div class="container">
                <div class="row">

                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="d-flex align-items-center justify-content-start py-2">
                            <div class="d_ico">
                                <i class="fas fa-shopping-basket"></i>
                            </div>
                            <div class="d_capt">
                                <h5 class="mb-0">Free Shipping</h5>
                                <span class="text-muted">Capped at $10 per order</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="d-flex align-items-center justify-content-start py-2">
                            <div class="d_ico">
                                <i class="far fa-credit-card"></i>
                            </div>
                            <div class="d_capt">
                                <h5 class="mb-0">Secure Payments</h5>
                                <span class="text-muted">Up to 6 months installments</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="d-flex align-items-center justify-content-start py-2">
                            <div class="d_ico">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="d_capt">
                                <h5 class="mb-0">15-Days Returns</h5>
                                <span class="text-muted">Shop with fully confidence</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="d-flex align-items-center justify-content-start py-2">
                            <div class="d_ico">
                                <i class="fas fa-headphones-alt"></i>
                            </div>
                            <div class="d_capt">
                                <h5 class="mb-0">24x7 Fully Support</h5>
                                <span class="text-muted">Get friendly support</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!-- ======================= Customer Features ======================== -->

        <!-- ============================ Footer Start ================================== -->
        <footer class="dark-footer skin-dark-footer style-2">
            <div class="footer-middle">
                <div class="container">
                    <div class="row">

                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                            <div class="footer_widget">
                                <img src="" class="img-footer small mb-2" alt="" />

                                <div class="address mt-3">
                                    3298 Grant Street Longview, TX<br>United Kingdom 75601
                                </div>
                                <div class="address mt-3">
                                    1-202-555-0106<br>help@shopper.com
                                </div>
                                <div class="address mt-3">
                                    <ul class="list-inline">
                                        <li class="list-inline-item"><a href="#"><i
                                                    class="lni lni-facebook-filled"></i></a></li>
                                        <li class="list-inline-item"><a href="#"><i
                                                    class="lni lni-twitter-filled"></i></a></li>
                                        <li class="list-inline-item"><a href="#"><i
                                                    class="lni lni-youtube"></i></a></li>
                                        <li class="list-inline-item"><a href="#"><i
                                                    class="lni lni-instagram-filled"></i></a></li>
                                        <li class="list-inline-item"><a href="#"><i
                                                    class="lni lni-linkedin-original"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
                            <div class="footer_widget">
                                <h4 class="widget_title">Supports</h4>
                                <ul class="footer-menu">
                                    @foreach ($footerMenus as $menu)
                                        <li>
                                            <a href="{{ $menu->url }}">{{ $menu->name }}</a>
                                            @if ($menu->children->isNotEmpty())
                                                <ul class="nav-dropdown nav-submenu">
                                                    @foreach ($menu->children as $child)
                                                        <li>
                                                            <a href="{{ $child->url }}">{{ $child->name }}</a>
                                                            @if ($child->children->isNotEmpty())
                                                                <ul class="nav-dropdown nav-submenu">
                                                                    @foreach ($child->children as $grandChild)
                                                                        <li>
                                                                            <a
                                                                                href="{{ $grandChild->url }}">{{ $grandChild->name }}</a>
                                                                            @if ($grandChild->children->isNotEmpty())
                                                                                <ul class="nav-dropdown nav-submenu">
                                                                                    @foreach ($grandChild->children as $greatGrandChild)
                                                                                        <li>
                                                                                            <a
                                                                                                href="{{ $greatGrandChild->url }}">{{ $greatGrandChild->name }}</a>
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            @endif
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
                            <div class="footer_widget">

                                <h4 class="widget_title">Track orders</h4>
                                <ul class="footer-menu">
                                    <li><a href="{{ route('track.orders.form') }}">Track your order</a></li>

                                </ul>
                            </div>
                        </div>



                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                            <div class="footer_widget">
                                <h4 class="widget_title">Subscribe</h4>
                                <p>Receive updates, hot deals, discounts sent straignt in your inbox daily</p>
                                <div class="foot-news-last">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Email Address">
                                        <div class="input-group-append">
                                            <button type="button" class="input-group-text b-0 text-light"><i
                                                    class="lni lni-arrow-right"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="address mt-3">
                                    <h5 class="fs-sm text-light">Secure Payments</h5>
                                    <div class="scr_payment"><img src="{{ asset('front-assets/img/card.png') }}"
                                            class="img-fluid" alt="" /></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-12 col-md-12 text-center">
                            <p class="mb-0">© 2021 Kumo. Designd By <a href="https://themezhub.com/">ThemezHub</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- ============================ Footer End ================================== -->


        <!-- Log In Modal -->
        <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
            aria-hidden="true">
            <div class="modal-dialog modal-xl login-pop-form" role="document">
                <div class="modal-content" id="loginmodal">
                    <div class="modal-headers">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="ti-close"></span>
                        </button>
                    </div>

                    <div class="modal-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="m-0 ft-regular">Login</h2>
                        </div>

                        <form action="{{ route('front.login') }}" method="POST" id="loginForm">
                            @csrf
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" placeholder="your@email.com"
                                    autocomplete="off" name="email" id="email" value="{{ old('email') }}">
                                @error('email')
                                    <p class="text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" placeholder="Password*" name="password"
                                    id="password">
                                @error('password')
                                    <p class="text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit"
                                    class="btn btn-md full-width bg-dark text-light fs-md ft-medium">Login</button>
                            </div>

                            <div class="form-group text-center mb-0">
                                <p class="extra">Not a member?<a href="{{ route('front.register') }}"
                                        class="text-dark">
                                        Register</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->

        <!-- Search -->
        <div class="w3-ch-sideBar w3-bar-block w3-card-2 w3-animate-right" style="display:none;right:0;"
            id="Search">
            <div class="rightMenu-scroll">
                <div class="d-flex align-items-center justify-content-between slide-head py-3 px-3">
                    <h4 class="cart_heading fs-md ft-medium mb-0">Search Products</h4>
                    <button onclick="closeSearch()" class="close_slide"><i class="ti-close"></i></button>
                </div>

                <div class="cart_action px-3 py-4">
                    <form id="search-form" class="form m-0 p-0">
                        <div class="form-group">
                            <input type="text" name="keyword" id="search-keyword" class="form-control"
                                placeholder="Product Keyword.." />
                        </div>

                        {{-- <div class="form-group">
                            <select class="custom-select">
                                <option value="1" selected="">Choose Category</option>
                                <option value="2">Men's Store</option>
                                <option value="3">Women's Store</option>
                                <option value="4">Kid's Fashion</option>
                                <option value="5">Inner Wear</option>
                            </select>
                        </div> --}}

                        <div class="form-group mb-0">
                            <button href="javascript:void(0);" type="button" id="search-button"
                                class="btn d-block full-width btn-dark">Search
                                Product</button>
                        </div>
                    </form>
                    <div id="search-results" class="mt-3"></div>
                </div>

                {{-- <div class="d-flex align-items-center justify-content-center br-top br-bottom py-2 px-3">
                    <h4 class="cart_heading fs-md mb-0">Hot Categories</h4>
                </div> --}}

                {{-- <div class="cart_action px-3 py-3">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-4 mb-3">
                            <div class="cats_side_wrap text-center">
                                <div class="sl_cat_01">
                                    <div
                                        class="d-inline-flex align-items-center justify-content-center p-3 circle mb-2 gray">
                                        <a href="javascript:void(0);" class="d-block"><img
                                                src="assets/img/tshirt.png" class="img-fluid" width="40"
                                                alt="" /></a>
                                    </div>
                                </div>
                                <div class="sl_cat_02">
                                    <h6 class="m-0 ft-medium fs-sm"><a href="javascript:void(0);">T-Shirts</a></h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-4 mb-3">
                            <div class="cats_side_wrap text-center">
                                <div class="sl_cat_01">
                                    <div
                                        class="d-inline-flex align-items-center justify-content-center p-3 circle mb-2 gray">
                                        <a href="javascript:void(0);" class="d-block"><img src="assets/img/pant.png"
                                                class="img-fluid" width="40" alt="" /></a>
                                    </div>
                                </div>
                                <div class="sl_cat_02">
                                    <h6 class="m-0 ft-medium fs-sm"><a href="javascript:void(0);">Pants</a></h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-4 mb-3">
                            <div class="cats_side_wrap text-center">
                                <div class="sl_cat_01">
                                    <div
                                        class="d-inline-flex align-items-center justify-content-center p-3 circle mb-2 gray">
                                        <a href="javascript:void(0);" class="d-block"><img
                                                src="assets/img/fashion.png" class="img-fluid" width="40"
                                                alt="" /></a>
                                    </div>
                                </div>
                                <div class="sl_cat_02">
                                    <h6 class="m-0 ft-medium fs-sm"><a href="javascript:void(0);">Women's</a></h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-4 mb-3">
                            <div class="cats_side_wrap text-center">
                                <div class="sl_cat_01">
                                    <div
                                        class="d-inline-flex align-items-center justify-content-center p-3 circle mb-2 gray">
                                        <a href="javascript:void(0);" class="d-block"><img
                                                src="assets/img/sneakers.png" class="img-fluid" width="40"
                                                alt="" /></a>
                                    </div>
                                </div>
                                <div class="sl_cat_02">
                                    <h6 class="m-0 ft-medium fs-sm"><a href="javascript:void(0);">Shoes</a></h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-4 mb-3">
                            <div class="cats_side_wrap text-center">
                                <div class="sl_cat_01">
                                    <div
                                        class="d-inline-flex align-items-center justify-content-center p-3 circle mb-2 gray">
                                        <a href="javascript:void(0);" class="d-block"><img
                                                src="assets/img/television.png" class="img-fluid" width="40"
                                                alt="" /></a>
                                    </div>
                                </div>
                                <div class="sl_cat_02">
                                    <h6 class="m-0 ft-medium fs-sm"><a href="javascript:void(0);">Television</a></h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-4 mb-3">
                            <div class="cats_side_wrap text-center">
                                <div class="sl_cat_01">
                                    <div
                                        class="d-inline-flex align-items-center justify-content-center p-3 circle mb-2 gray">
                                        <a href="javascript:void(0);" class="d-block"><img
                                                src="assets/img/accessories.png" class="img-fluid" width="40"
                                                alt="" /></a>
                                    </div>
                                </div>
                                <div class="sl_cat_02">
                                    <h6 class="m-0 ft-medium fs-sm"><a href="javascript:void(0);">Accessories</a></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

            </div>
        </div>
        <script>
            document.getElementById('search-button').addEventListener('click', function() {
                const keyword = document.getElementById('search-keyword').value;

                fetch("{{ route('front.shop') }}?keyword=" + encodeURIComponent(keyword), {
                        method: "GET",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        displayResults(data.products);
                    })
                    .catch(error => console.error("Error:", error));
            });

            function displayResults(products) {
                const resultsContainer = document.getElementById('search-results');
                resultsContainer.innerHTML = ""; // Clear previous results

                if (products.length === 0) {
                    resultsContainer.innerHTML = "<p>No products found.</p>";
                    return;
                }

                products.forEach(product => {
                    const productHTML = `
                        <div class="product-item">
                            <img src="${product.image_url}" style="width:100px; height:auto; display:block; margin-bottom:10px;">
                            <h5>
                                <a href="{{ route('product.show', '') }}/${product.slug}">${product.title}</a>
                            </h5>

                            <p><strong>Price:</strong> Rs.${product.price}</p>
                        </div>
                    `;
                    resultsContainer.innerHTML += productHTML;
                });
            }
        </script>


        <!-- Wishlist -->
        <div class="w3-ch-sideBar w3-bar-block w3-card-2 w3-animate-right" style="display:none;right:0;"
            id="Wishlist">
            <div class="rightMenu-scroll">
                <div class="d-flex align-items-center justify-content-between slide-head py-3 px-3">
                    <h4 class="cart_heading fs-md ft-medium mb-0">Saved Products</h4>
                    <button onclick="closeWishlist()" class="close_slide"><i class="ti-close"></i></button>
                </div>
                <div class="right-ch-sideBar">

                    <div class="cart_select_items py-2">
                        <!-- Single Item -->
                        <div class="d-flex align-items-center justify-content-between br-bottom px-3 py-3">
                            <div class="cart_single d-flex align-items-center">
                                <div class="cart_selected_single_thumb">
                                    <a href="#"><img src="{{ asset('front-assets/img/product/4.jpg') }}"
                                            width="60" class="img-fluid" alt="" /></a>
                                </div>
                                <div class="cart_single_caption pl-2">
                                    <h4 class="product_title fs-sm ft-medium mb-0 lh-1">Women Striped Shirt Dress</h4>
                                    <p class="mb-2"><span class="text-dark ft-medium small">36</span>, <span
                                            class="text-dark small">Red</span></p>
                                    <h4 class="fs-md ft-medium mb-0 lh-1">$129</h4>
                                </div>
                            </div>
                            <div class="fls_last"><button class="close_slide gray"><i class="ti-close"></i></button>
                            </div>
                        </div>

                        <!-- Single Item -->
                        <div class="d-flex align-items-center justify-content-between br-bottom px-3 py-3">
                            <div class="cart_single d-flex align-items-center">
                                <div class="cart_selected_single_thumb">
                                    <a href="#"><img src="{{ asset('front-assets/img/product/7.jpg') }}"
                                            width="60" class="img-fluid" alt="" /></a>
                                </div>
                                <div class="cart_single_caption pl-2">
                                    <h4 class="product_title fs-sm ft-medium mb-0 lh-1">Girls Floral Print Jumpsuit
                                    </h4>
                                    <p class="mb-2"><span class="text-dark ft-medium small">36</span>, <span
                                            class="text-dark small">Red</span></p>
                                    <h4 class="fs-md ft-medium mb-0 lh-1">$129</h4>
                                </div>
                            </div>
                            <div class="fls_last"><button class="close_slide gray"><i class="ti-close"></i></button>
                            </div>
                        </div>

                        <!-- Single Item -->
                        <div class="d-flex align-items-center justify-content-between px-3 py-3">
                            <div class="cart_single d-flex align-items-center">
                                <div class="cart_selected_single_thumb">
                                    <a href="#"><img src="{{ asset('front-assets/img/product/8.jpg') }}"
                                            width="60" class="img-fluid" alt="" /></a>
                                </div>
                                <div class="cart_single_caption pl-2">
                                    <h4 class="product_title fs-sm ft-medium mb-0 lh-1">Girls Solid A-Line Dress</h4>
                                    <p class="mb-2"><span class="text-dark ft-medium small">30</span>, <span
                                            class="text-dark small">Blue</span></p>
                                    <h4 class="fs-md ft-medium mb-0 lh-1">$100</h4>
                                </div>
                            </div>
                            <div class="fls_last"><button class="close_slide gray"><i class="ti-close"></i></button>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex align-items-center justify-content-between br-top br-bottom px-3 py-3">
                        <h6 class="mb-0">Subtotal</h6>
                        <h3 class="mb-0 ft-medium">$417</h3>
                    </div>

                    <div class="cart_action px-3 py-3">
                        <div class="form-group">
                            <button type="button" class="btn d-block full-width btn-dark">Move To Cart</button>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn d-block full-width btn-dark-light">Edit or View</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Cart -->
        <div class="w3-ch-sideBar w3-bar-block w3-card-2 w3-animate-right" style="display:none;right:0;"
            id="Cart">
            <div class="rightMenu-scroll">
                <div class="d-flex align-items-center justify-content-between slide-head py-3 px-3">
                    <h4 class="cart_heading fs-md ft-medium mb-0">Products List</h4>
                    <button onclick="closeCart()" class="close_slide"><i class="ti-close"></i></button>
                </div>
                <div class="right-ch-sideBar">

                    <div class="cart_select_items py-2">
                        <!-- Single Item -->
                        <div class="d-flex align-items-center justify-content-between br-bottom px-3 py-3">
                            <div class="cart_single d-flex align-items-center">
                                <div class="cart_selected_single_thumb">
                                    <a href="#"><img src="{{ asset('front-assets/img/product/4.jpg') }}"
                                            width="60" class="img-fluid" alt="" /></a>
                                </div>
                                <div class="cart_single_caption pl-2">
                                    <h4 class="product_title fs-sm ft-medium mb-0 lh-1">Women Striped Shirt Dress</h4>
                                    <p class="mb-2"><span class="text-dark ft-medium small">36</span>, <span
                                            class="text-dark small">Red</span></p>
                                    <h4 class="fs-md ft-medium mb-0 lh-1">$129</h4>
                                </div>
                            </div>
                            <div class="fls_last"><button class="close_slide gray"><i class="ti-close"></i></button>
                            </div>
                        </div>

                        <!-- Single Item -->
                        <div class="d-flex align-items-center justify-content-between br-bottom px-3 py-3">
                            <div class="cart_single d-flex align-items-center">
                                <div class="cart_selected_single_thumb">
                                    <a href="#"><img src="{{ asset('front-assets/img/product/7.jpg') }}"
                                            width="60" class="img-fluid" alt="" /></a>
                                </div>
                                <div class="cart_single_caption pl-2">
                                    <h4 class="product_title fs-sm ft-medium mb-0 lh-1">Girls Floral Print Jumpsuit
                                    </h4>
                                    <p class="mb-2"><span class="text-dark ft-medium small">36</span>, <span
                                            class="text-dark small">Red</span></p>
                                    <h4 class="fs-md ft-medium mb-0 lh-1">$129</h4>
                                </div>
                            </div>
                            <div class="fls_last"><button class="close_slide gray"><i class="ti-close"></i></button>
                            </div>
                        </div>

                        <!-- Single Item -->
                        <div class="d-flex align-items-center justify-content-between px-3 py-3">
                            <div class="cart_single d-flex align-items-center">
                                <div class="cart_selected_single_thumb">
                                    <a href="#"><img src="{{ asset('front-assets/img/product/8.jpg') }}"
                                            width="60" class="img-fluid" alt="" /></a>
                                </div>
                                <div class="cart_single_caption pl-2">
                                    <h4 class="product_title fs-sm ft-medium mb-0 lh-1">Girls Solid A-Line Dress</h4>
                                    <p class="mb-2"><span class="text-dark ft-medium small">30</span>, <span
                                            class="text-dark small">Blue</span></p>
                                    <h4 class="fs-md ft-medium mb-0 lh-1">$100</h4>
                                </div>
                            </div>
                            <div class="fls_last"><button class="close_slide gray"><i class="ti-close"></i></button>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex align-items-center justify-content-between br-top br-bottom px-3 py-3">
                        <h6 class="mb-0">Subtotal</h6>
                        <h3 class="mb-0 ft-medium">$1023</h3>
                    </div>

                    <div class="cart_action px-3 py-3">
                        <div class="form-group">
                            <button type="button" class="btn d-block full-width btn-dark">Checkout Now</button>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn d-block full-width btn-dark-light">Edit or View</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <a id="back2Top" class="top-scroll" title="Back to top" href="#"><i class="ti-arrow-up"></i></a>


    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{ asset('front-assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/slick.js') }}"></script>
    <script src="{{ asset('front-assets/js/slider-bg.js') }}"></script>
    <script src="{{ asset('front-assets/js/lightbox.js') }}"></script>
    <script src="{{ asset('front-assets/js/smoothproducts.js') }}"></script>
    <script src="{{ asset('front-assets/js/snackbar.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/jQuery.style.switcher.js') }}"></script>
    <script src="{{ asset('front-assets/js/custom.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->

    {{-- <script>
			function openWishlist() {
				document.getElementById("Wishlist").style.display = "block";
			}
			function closeWishlist() {
				document.getElementById("Wishlist").style.display = "none";
			}
		</script> --}}

    {{-- <script>
			function openCart() {
				document.getElementById("Cart").style.display = "block";
			}
			function closeCart() {
				document.getElementById("Cart").style.display = "none";
			}
		</script> --}}

    <script>
        function openSearch() {
            document.getElementById("Search").style.display = "block";
        }

        function closeSearch() {
            document.getElementById("Search").style.display = "none";
        }
    </script>

</body>

</html>
