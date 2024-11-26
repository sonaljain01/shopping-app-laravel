<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Wishlistcontroller;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\admin\AdminOrderController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\AttributeController;
use App\Http\Controllers\admin\StateController;
use App\Http\Controllers\admin\PickupController;


use App\Http\Controllers\admin\CityController;
use App\Http\Controllers\admin\MenuController;

Route::get('/', [FrontController::class, 'index'])->name('front.home');

Route::get('/shop/{categorySlug?}/{subcategorySlug?}', [ShopController::class, 'index'])->name('front.shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/products/{id}', [ShopController::class, 'show']);
Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.index');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');


Route::get('/product/{id}/quick-view', [ProductController::class, 'quickView'])->name('product.quickview');
// Route::get('/register/form', [AuthController::class, 'showRegistrationForm'])->name('front.register');
Route::post('register', [AuthController::class, 'register'])->name('front.register.store');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('front.register');

Route::post('/login', [AuthController::class, 'login'])->name('front.login');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/facebook', [FacebookController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);

Route::get('/checkout', [OrderController::class, 'checkout'])->name('front.checkout');
Route::post('/checkout/place-order', [OrderController::class, 'placeOrder'])->name('checkout.placeOrder');
Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('front.index');
Route::get('/front/{user}', [OrderController::class, 'index'])->name('front.index.user');
Route::get('/check-delivery/{city}/{state}', [OrderController::class, 'checkDelivery'])->name('check-delivery');
Route::post('/billing-address/save', [OrderController::class, 'save'])->name('billing.address.save');

Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');

Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::delete('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');

});

//track orders
Route::get('/track-orders', [OrderController::class, 'showTrackOrderForm'])->name('track.orders.form');
Route::post('/track-orders', [OrderController::class, 'trackOrder'])->name('track.orders');


//price filter
Route::get('price/filter', [ShopController::class, 'filter'])->name('price.filter');



// admin routes
Route::group(['prefix' => 'admin'], function () {
    Route::middleware(['admin.guest'])->group(function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });
    Route::middleware(['admin.auth'])->group(function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        //category Routes
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::post('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.delete');

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

        //brands
        Route::get('brands/create', [BrandController::class, 'create'])->name('brands.create');
        Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
        Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
        Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.delete');
        Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');


        //Products Routes
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::post('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.delete');


        Route::get('/getSlug', function (Request $request) {
            $slug = '';
            if (!empty($request->title)) {
                $slug = \Str::slug($request->title);
            }

            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);

        })->name('getSlug');

        // Order routes
        Route::get('/orders', [AdminOrderController::class, 'index'])->name(name: 'orders.index');
        Route::get('/orders/{id}', [AdminOrderController::class, 'detail'])->name(name: 'orders.detail');
        Route::post('/order/change-status/{id}', [AdminOrderController::class, 'changeOrderStatus'])->name(name: 'orders.changeOrderStatus');
        Route::get('/orders/{orderId}/view-invoice', [AdminOrderController::class, 'viewInvoice'])
            ->name('admin.orders.viewInvoice');
        Route::get('orders/{orderId}/print-invoice', [AdminOrderController::class, 'printInvoice'])->name('orders.printInvoice');


        //users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.delete');
        Route::get('/admin/users/{user}/login-as-customer', [UserController::class, 'loginAsCustomer'])->name('admin.loginAsCustomer');
        Route::get('/customer/{user}/profile', [UserController::class, 'showProfile'])->name('customer.profile');
        Route::get('/admin/restore-session', [UserController::class, 'restoreAdminSession'])->name('admin.restoreAdminSession');



        

        Route::get('/attributes', [AttributeController::class, 'index'])->name('attributes.index');
        Route::get('attributes/create', [AttributeController::class, 'create'])->name('attributes.create');
        Route::post('/attributes', [AttributeController::class, 'store'])->name('attributes.store');
        Route::get('/admin/attributes/{attributeId}/add-values', [AttributeController::class, 'showAddValuesForm'])->name('attributes.addValues');
        Route::post('/admin/attributes/{attributeId}/store-values', [AttributeController::class, 'storeValues'])->name('attributes.storeValues');
        Route::get('/admin/attributes/{attributeId}/values', [AttributeController::class, 'getAttributeValues'])->name('attributes.getValues');
        Route::get('/admin/attributes/form', [AttributeController::class, 'showAttributesForm'])->name('attributes.form');
        Route::get('/attributes/{id}/edit', [AttributeController::class, 'edit'])->name('attributes.edit');
        Route::put('/attributes/{id}', [AttributeController::class, 'update'])->name('attributes.update');
        Route::delete('/attributes/{id}', [AttributeController::class, 'destroy'])->name('attributes.destroy');

        Route::get('/get-attribute-values/{attribute_id}', function ($attribute_id) {
            $values = \App\Models\AttributeValue::where('attribute_id', $attribute_id)->get();
            return response()->json(['values' => $values]);
        });


        // bulk imports products
        Route::get('/bulk-import', [ProductController::class, 'bulkImportDashboard'])->name('admin.bulk-import');
        Route::post('/bulk-import', [ProductController::class, 'importProducts'])->name('admin.products.import');

        //download csv
        Route::get('/admin/products/template', [ProductController::class, 'downloadProductTemplate'])->name('admin.products.template');
        Route::get('/admin/products/categories', [ProductController::class, 'downloadCategories'])->name('admin.products.categories');
        Route::get('/admin/products/brands', [ProductController::class, 'downloadBrands'])->name('admin.products.brands');
        Route::get('/admin/products/attributes', [ProductController::class, 'downloadAttributes'])->name('admin.products.attributes');
        Route::get('download-attribute-values', [ProductController::class, 'downloadAttributeValues'])->name('admin.download.attribute-values');


        //states
        Route::get('/states', [StateController::class, 'index'])->name('state.index');
        Route::patch('/states/{id}/toggle', [StateController::class, 'toggleStatus'])->name('state.toggle');
        Route::get('/states/create', [StateController::class, 'create'])->name('state.create');
        Route::post('/states/create', [StateController::class, 'storeState'])->name('state.store');
        Route::get('states/{id}/edit', [StateController::class, 'edit'])->name('state.edit');
        Route::put('states/{id}', [StateController::class, 'update'])->name('state.update');
        Route::delete('/states/{state}', [StateController::class, 'destroy'])->name('state.delete');
        //cities
        Route::get('/cities', [CityController::class, 'index'])->name('city.index');
        Route::post('/cities', [CityController::class, 'storeCity'])->name('city.store');
        Route::patch('/cities/{id}/toggle', [CityController::class, 'toggleCityDelivery'])->name('city.toggle');
        Route::get('/cities/create', [CityController::class, 'create'])->name('city.create');
        Route::post('/cities/create', [CityController::class, 'storeCity'])->name('city.store');
        Route::get('cities/{id}/edit', [CityController::class, 'edit'])->name('city.edit');
        Route::put('cities/{id}', [CityController::class, 'update'])->name('city.update');
        Route::delete('/cities/{city}', [CityController::class, 'destroy'])->name('city.delete');

        //pickup routes
        Route::get('/pickup', [PickupController::class, 'index'])->name('pickup.index');
        Route::post('/pickup', [PickupController::class, 'store'])->name('pickup.store');
        // Route::patch('/cities/{id}/toggle', [CityController::class, 'toggleCityDelivery'])->name('city.toggle');
        Route::get('/pickup/create', [PickupController::class, 'create'])->name('pickup.create');
        Route::get('pickup/{id}/edit', [PickupController::class, 'edit'])->name('pickup.edit');
        Route::put('pickup/{id}', [PickupController::class, 'update'])->name('pickup.update');
        Route::delete('/pickup/{pickup}', [PickupController::class, 'destroy'])->name('pickup.destroy');

        //menu routes

        Route::resource('menus', MenuController::class, [
            'names' => [
                'index' => 'admin.menus.index',
                'create' => 'admin.menus.create',
                'store' => 'admin.menus.store',
                'show' => 'admin.menus.show',
                'edit' => 'admin.menus.edit',
                'update' => 'admin.menus.update',
                'destroy' => 'admin.menus.destroy',
               
            ]
        ]);
        Route::post('/admin/menus/update-order', [MenuController::class, 'updateOrder'])->name('admin.menus.updateOrder');
        Route::get('admin/menus/manage-locations', [MenuController::class, 'manageLocations'])->name('admin.menus.manageLocations');
        Route::post('admin/menus/update-locations', [MenuController::class, 'updateLocations'])->name('admin.menus.updateLocations');

    });


});




