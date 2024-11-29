<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('admin-assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">LARAVEL SHOP</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>																
                </li>
                <li class="nav-item">
                    <a href="{{ route('categories.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Category</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('brands.index') }}" class="nav-link">
                        <svg class="h-6 nav-icon w-6 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                          </svg>
                        <p>Brands</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('products.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-tag"></i>
                        <p>Products</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.bulk-import') }}" class="nav-link">
                        <i class="nav-icon fas fa-tag"></i>
                        <p>Bulk Import</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('attributes.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-columns"></i>
                        <p>Attributes</p>
                    </a>
                </li>
                
                
                <li class="nav-item {{ request()->routeIs('state.index') || request()->routeIs('city.index') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-truck nav-icon"></i>
                        <p>
                            Shipping
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('state.index') }}" class="nav-link {{ request()->routeIs('state.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Shipping States</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('city.index') }}" class="nav-link {{ request()->routeIs('city.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Shipping Cities</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pickup.index') }}" class="nav-link {{ request()->routeIs('pickup.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>PickUp Address</p>
                            </a>
                        </li>
                    </ul>
                </li>
                                        
                <li class="nav-item">
                    <a href="{{ route('orders.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-shopping-bag"></i>
                        <p>Orders</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="discount.html" class="nav-link">
                        <i class="nav-icon  fa fa-percent" aria-hidden="true"></i>
                        <p>Discount</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link">
                        <i class="nav-icon  fas fa-users"></i>
                        <p>Customers</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pages.html" class="nav-link">
                        <i class="nav-icon  far fa-file-alt"></i>
                        <p>Pages</p>
                    </a>
                </li>	
                <li class="nav-item">
                    <a href="{{ route('admin.menus.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Menus</p>
                    </a>
                </li>	
                <li class="nav-item {{ request()->routeIs('settings.edit') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-truck nav-icon"></i>
                        <p>
                            Settings
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('settings.edit') }}" class="nav-link {{ request()->routeIs('settings.edit') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>ShipRocket</p>
                            </a>
                        </li>
                    </ul>
                </li>				
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
 </aside>