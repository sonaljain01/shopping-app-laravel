<div class="col-12 col-md-12 col-lg-4 col-xl-4 text-center miliods">
    <div class="d-block border rounded">
        <div class="dashboard_author px-2 py-5">
            
            <div class="dash_caption">
                <h4 class="fs-md ft-medium mb-0 lh-1">{{ __('user') }}</h4>
            </div>
        </div>

        <div class="dashboard_author">
            <h4 class="px-3 py-2 mb-0 lh-2 gray fs-sm ft-medium text-muted text-uppercase text-left">{{ __('Dashboard Navigation') }}</h4>
            <ul class="dahs_navbar">
                <li>
                    <a href="{{ route('front.index') }}" class="{{ request()->routeIs('front.index') ? 'text-danger' : '' }}">
                        <i class="lni lni-shopping-basket mr-2"></i>{{ __('My Order') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('wishlist.index') }}" class="{{ request()->routeIs('wishlist.index') ? 'text-danger' : '' }}">
                        <i class="lni lni-heart mr-2"></i>{{ __('wishlist') }}
                    </a>
                </li>
                <li>
                    <a href="#" class="{{ request()->is('profile-info') ? 'text-danger' : '' }}">
                        <i class="lni lni-user mr-2"></i>{{ __('Profile Info') }}
                    </a>
                </li>
                <li>
                    <a href="addresses.html" class="{{ request()->is('addresses') ? 'text-danger' : '' }}">
                        <i class="lni lni-map-marker mr-2"></i>{{ __('address') }}
                    </a>
                </li>
                <li>
                    <a href="payment-methode.html" class="{{ request()->is('payment-methode') ? 'text-danger' : '' }}">
                        <i class="lni lni-mastercard mr-2"></i>{{ __('Payment Method') }}
                    </a>
                </li>
                <li>
                    <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="{{ request()->is('login') ? 'text-danger' : '' }}">
                        <i class="lni lni-power-switch mr-2"></i>{{ __('logout') }}
                    </a>
                </li>
                
            </ul>
        </div>
    </div>
</div>
