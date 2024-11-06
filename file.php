<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null)
    {
        $categories = $this->getActiveCategories();
        $brands = $this->getActiveBrands();
        
        $productsQuery = Product::where('status', 1);
        
        if ($categorySlug) {
            $this->applyCategoryFilter($productsQuery, $categorySlug);
        }

        if ($request->has('brand')) {
            $this->applyBrandFilter($productsQuery, $request->input('brand'));
        }

        $this->applyPriceFilter($productsQuery, $request);
        
        $this->applySorting($productsQuery, $request->input('sort'));

        $products = $productsQuery->get();
        if ($request->ajax()) {
            return view('front.shop', [
                'products' => $products,
            ]);
        }
        return view('front.shop', [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
            'categorySelected' => $request->input('categorySelected'), 
            'subCategorySelected' => $request->input('subCategorySelected'), 
            'sort' => $request->input('sort'),
        ]);
    }

    protected function getActiveCategories()
    {
        return Category::with('children')->where('status', 1)->orderBy('name', 'ASC')->get();
    }

    protected function getActiveBrands()
    {
        return Brand::where('status', 1)->orderBy('name', 'ASC')->get();
    }

    protected function applyCategoryFilter($query, $categorySlug)
    {
        $category = Category::where('slug', $categorySlug)->where('status', 1)->first();
        if ($category) {
            $query->where('category_id', $category->id);
        }
        else{
            $query->where('category_id', 0);
        }
    }

    protected function applyBrandFilter($query, $brandIds)
    {
        if (!empty($brandIds)) {
            $query->whereIn('brand_id', $brandIds);
        }
        else {
            $query->where('brand_id', 0);
        }
    }

    protected function applyPriceFilter($query, Request $request)
    {
        $minPrice = floatval($request->input('price_min', 0));
        $maxPrice = floatval($request->input('price_max', PHP_INT_MAX));

        if ($minPrice >= 0 && $minPrice <= $maxPrice) {
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        }
    }

    protected function applySorting($query, $sort)
    {
        switch ($sort) {
            case 'price_low_high':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high_low':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'trending':
                $query->orderBy('trending_score', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
                break;
        }
    }
}




































<div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 p-xl-0">
    <div class="search-sidebar sm-sidebar border">
        <div class="search-sidebar-body">
            <!-- Single Option -->
            <div class="single_search_boxed">
                <div class="widget-boxed-header px-3">
                    <h4 class="mt-3">Categories</h4>
                </div>
                <div class="widget-boxed-body">
                    <div class="side-list no-border">
                        <div class="filter-card" id="shop-categories">

                            @foreach ($categories as $category)
                                <div class="single_filter_card">
                                    <h5>
                                        <a href="#" data-toggle="collapse" class="collapsed" aria-expanded="false"
                                            role="button" data-target="#category-{{ $category->id }}">
                                            {{ $category->name }}<i class="accordion-indicator ti-angle-down"></i>
                                        </a>
                                    </h5>

                                    {{-- Collapse content for child categories --}}
                                    <div class="collapse" id="category-{{ $category->id }}"
                                        data-parent="#shop-categories">
                                        <div class="card-body">
                                            <div class="inner_widget_link">
                                                <ul>
                                                    @foreach ($category->children as $child)
                                                        <li>
                                                            <a
                                                                href="{{ route('front.shop', [$category->slug, $child->slug]) }}">
                                                                {{ $child->name }}
                                                                <span>({{ $child->product_count }})</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>

            <!-- Single Option -->
            {{-- <div class="single_search_boxed">
                <div class="widget-boxed-header">
                    
                    <h4><a href="#pricing" data-toggle="collapse" aria-expanded="false"
                            role="button" >Pricing</a></h4>
                    
                </div>
                <div class="widget-boxed-body collapse show" id="pricing" data-parent="#pricing">
                    <div class="side-list no-border mb-4">
                        <div class="rg-slider">
                            
                            <input type="text" class="js-range-slider" name="my_range" value="" />
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="single_search_boxed">
                <div class="widget-boxed-header">
                    <h4><a href="#pricing" data-toggle="collapse" aria-expanded="false" role="button">Pricing</a></h4>
                </div>
                <div class="widget-boxed-body collapse show" id="pricing" data-parent="#pricing">
                    <div class="side-list no-border mb-4">
                        <div class="rg-slider">
                            <input type="text" class="js-range-slider" name="my_range" value="" id="my_range"
                                onchange="updateTextInput(this.value)" />
                        </div>
                    </div>
                </div>
            </div>
            <!-- Product list container -->
            <div id="products" class="row">
                <!-- Products will be inserted here dynamically -->
                {{-- diaply products --}}

            </div>



            <div id="product_count"></div>

            <script>
                // Initialize noUiSlider
                var slider = document.getElementById('price-range-slider');
                noUiSlider.create(slider, {
                    start: [0, 10000], // Default range
                    connect: true,
                    range: {
                        'min': 0,
                        'max': 10000
                    },
                    step: 10,
                    format: wNumb({
                        decimals: 0,
                        prefix: '₹'
                    })
                });

                // Display the selected range
                slider.noUiSlider.on('update', function(values, handle) {
                    var value = values.map(function(value) {
                        return value.replace('₹', ''); // Remove ₹ sign for backend processing
                    });
                    document.querySelector('#slider-value').innerText = 'Price Range: ₹' + value[0] + ' - ₹' + value[1];
                });

                // Fetch filtered products when the slider value changes
                let debounceTimeout;

                async function updateTextInput(value) {
                    const productsContainer = document.querySelector('#products');
                    const productsCount = document.querySelector('#product_count');

                    // Clear any existing products
                    productsContainer.innerHTML = '';

                    clearTimeout(debounceTimeout);
                    debounceTimeout = setTimeout(async () => {
                        const parts = value.split(";");
                        if (parts.length !== 2) {
                            console.error("Invalid input format");
                            return;
                        }

                        const data = {
                            min: parts[0],
                            max: parts[1]
                        };

                        try {
                            // Fetch filtered products
                            const res = await fetch("/price/filter", {
                                method: 'POST',
                                headers: {
                                    "Content-Type": "application/json",
                                    'X-CSRF-Token': "{{ csrf_token() }}",
                                },
                                body: JSON.stringify(data)
                            });

                            if (!res.ok) {
                                throw new Error(`Request failed with status ${res.status}`);
                            }

                            const response = await res.json();

                            // Check if products are returned
                            if (response.product && response.product.length > 0) {
                                productsCount.innerText = `${response.product.length} Items Found`;

                                response.product.forEach(product => {
                                    const productHTML = `
                        <div class="col-xl-4 col-lg-4 col-md-6 col-6">
                            <div class="product_grid card b-0">
                                <div class="badge bg-info text-white position-absolute ft-regular ab-left text-upper">New</div>
                                <div class="card-body p-0">
                                    <div class="shop_thumb position-relative">
                                        <a class="card-img-top d-block overflow-hidden" href="/product/{slug}/show/${product.slug}">
                                            <img class="card-img-top" src="{{ asset('uploads/product') }}/${product.thumbnail}" alt="${product.title}">
                                        </a>
                                    </div>
                                </div>
                                <div class="card-footer b-0 p-0 pt-2 bg-white">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <div class="text-left"></div>
                                        <div class="text-right">
                                            <button class="btn auto btn_love snackbar-wishlist" id="wishlist">
                                                <i class="far fa-heart"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="text-left">
                                        <h5 class="fw-bolder fs-md mb-0 lh-1 mb-1">
                                            <a href="/product/{slug}/show/${product.slug}">${product.title}</a>
                                        </h5>
                                        <div class="elis_rty">
                                            <span class="ft-bold text-dark fs-sm">₹${product.price}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                                    productsContainer.insertAdjacentHTML('beforeend', productHTML);
                                });
                            } else {
                                productsContainer.innerHTML = '<p>No products found in this price range.</p>';
                            }

                        } catch (error) {
                            console.error("Error fetching products:", error);
                            productsContainer.innerHTML = '<p>Something went wrong while fetching products.</p>';
                        }
                    }, 300);
                }
            </script>


            <!-- Single Option -->
            <div class="single_search_boxed">
                <div class="widget-boxed-header">
                    <h4><a href="#size" data-toggle="collapse" class="collapsed" aria-expanded="false"
                            role="button">Size</a></h4>
                </div>
                <div class="widget-boxed-body collapse" id="size" data-parent="#size">
                    <div class="side-list no-border">
                        <!-- Single Filter Card -->
                        <div class="single_filter_card">
                            <div class="card-body pt-0">
                                <div class="text-left pb-0 pt-2">
                                    <div class="form-check form-option form-check-inline mb-2">
                                        <input class="form-check-input" type="radio" name="sizes" id="26s">
                                        <label class="form-option-label" for="26s">26</label>
                                    </div>
                                    <div class="form-check form-option form-check-inline mb-2">
                                        <input class="form-check-input" type="radio" name="sizes" id="28s">
                                        <label class="form-option-label" for="28s">28</label>
                                    </div>
                                    <div class="form-check form-option form-check-inline mb-2">
                                        <input class="form-check-input" type="radio" name="sizes" id="30s"
                                            checked>
                                        <label class="form-option-label" for="30s">30</label>
                                    </div>
                                    <div class="form-check form-option form-check-inline mb-2">
                                        <input class="form-check-input" type="radio" name="sizes" id="32s">
                                        <label class="form-option-label" for="32s">32</label>
                                    </div>
                                    <div class="form-check form-option form-check-inline mb-2">
                                        <input class="form-check-input" type="radio" name="sizes" id="34s">
                                        <label class="form-option-label" for="34s">34</label>
                                    </div>
                                    <div class="form-check form-option form-check-inline mb-2">
                                        <input class="form-check-input" type="radio" name="sizes" id="36s">
                                        <label class="form-option-label" for="36s">36</label>
                                    </div>
                                    <div class="form-check form-option form-check-inline mb-2">
                                        <input class="form-check-input" type="radio" name="sizes"
                                            id="38s">
                                        <label class="form-option-label" for="38s">38</label>
                                    </div>
                                    <div class="form-check form-option form-check-inline mb-2">
                                        <input class="form-check-input" type="radio" name="sizes"
                                            id="40s">
                                        <label class="form-option-label" for="40s">40</label>
                                    </div>
                                    <div class="form-check form-option form-check-inline mb-2">
                                        <input class="form-check-input" type="radio" name="sizes"
                                            id="42s">
                                        <label class="form-option-label" for="42s">42</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Single Option -->
            <div class="single_search_boxed">
                <div class="widget-boxed-header">
                    <h4><a href="#brands" data-toggle="collapse" aria-expanded="false" role="button">Brands</a>
                    </h4>
                </div>
                <div class="widget-boxed-body collapse show" id="brands" data-parent="#brands">
                    <div class="side-list no-border">
                        <!-- Single Filter Card -->

                        <div class="card-body">
                            <form method="GET" action="{{ url()->current() }}">
                                @if ($brands->count() > 0)
                                    @foreach ($brands as $brand)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input brand-label" type="checkbox"
                                                name="brand[]" value="{{ $brand->id }}"
                                                id="brand-{{ $brand->id }}"
                                                {{ in_array($brand->id, request()->input('brand', [])) ? 'checked' : '' }}
                                                onchange="this.form.submit()">
                                            <label class="form-check-label" for="brand-{{ $brand->id }}">
                                                {{ $brand->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                            </form>
                        </div>

                    </div>
                </div>

            </div>

            <!-- Single Option -->
            <div class="single_search_boxed">
                <div class="widget-boxed-header">
                    <h4><a href="#gender" data-toggle="collapse" class="collapsed" aria-expanded="false"
                            role="button">Gender</a></h4>
                </div>
                <div class="widget-boxed-body collapse" id="gender" data-parent="#gender">
                    <div class="side-list no-border">
                        <!-- Single Filter Card -->
                        <div class="single_filter_card">
                            <div class="card-body pt-0">
                                <div class="inner_widget_link">
                                    <ul class="no-ul-list">
                                        <li>
                                            <input id="g1" class="checkbox-custom" name="g1"
                                                type="checkbox">
                                            <label for="g1"
                                                class="checkbox-custom-label">All<span>22</span></label>
                                        </li>
                                        <li>
                                            <input id="g2" class="checkbox-custom" name="g2"
                                                type="checkbox">
                                            <label for="g2"
                                                class="checkbox-custom-label">Male<span>472</span></label>
                                        </li>
                                        <li>
                                            <input id="g3" class="checkbox-custom" name="g3"
                                                type="checkbox">
                                            <label for="g3"
                                                class="checkbox-custom-label">Female<span>170</span></label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Single Option -->
            <div class="single_search_boxed">
                <div class="widget-boxed-header">
                    <h4><a href="#discount" data-toggle="collapse" class="collapsed" aria-expanded="false"
                            role="button">Discount</a></h4>
                </div>
                <div class="widget-boxed-body collapse" id="discount" data-parent="#discount">
                    <div class="side-list no-border">
                        <!-- Single Filter Card -->
                        <div class="single_filter_card">
                            <div class="card-body pt-0">
                                <div class="inner_widget_link">
                                    <ul class="no-ul-list">
                                        <li>
                                            <input id="d1" class="checkbox-custom" name="d1"
                                                type="checkbox">
                                            <label for="d1" class="checkbox-custom-label">80%
                                                Discount<span>22</span></label>
                                        </li>
                                        <li>
                                            <input id="d2" class="checkbox-custom" name="d2"
                                                type="checkbox">
                                            <label for="d2" class="checkbox-custom-label">60%
                                                Discount<span>472</span></label>
                                        </li>
                                        <li>
                                            <input id="d3" class="checkbox-custom" name="d3"
                                                type="checkbox">
                                            <label for="d3" class="checkbox-custom-label">50%
                                                Discount<span>170</span></label>
                                        </li>
                                        <li>
                                            <input id="d4" class="checkbox-custom" name="d4"
                                                type="checkbox">
                                            <label for="d4" class="checkbox-custom-label">40%
                                                Discount<span>170</span></label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Single Option -->
            <div class="single_search_boxed">
                <div class="widget-boxed-header">
                    <h4><a href="#types" data-toggle="collapse" class="collapsed" aria-expanded="false"
                            role="button">Type</a></h4>
                </div>
                <div class="widget-boxed-body collapse" id="types" data-parent="#types">
                    <div class="side-list no-border">
                        <!-- Single Filter Card -->
                        <div class="single_filter_card">
                            <div class="card-body pt-0">
                                <div class="inner_widget_link">
                                    <ul class="no-ul-list">
                                        <li>
                                            <input id="t1" class="checkbox-custom" name="t1"
                                                type="checkbox">
                                            <label for="t1" class="checkbox-custom-label">All
                                                Type<span>422</span></label>
                                        </li>
                                        <li>
                                            <input id="t2" class="checkbox-custom" name="t2"
                                                type="checkbox">
                                            <label for="t2" class="checkbox-custom-label">Normal
                                                Type<span>472</span></label>
                                        </li>
                                        <li>
                                            <input id="t3" class="checkbox-custom" name="t3"
                                                type="checkbox">
                                            <label for="t3" class="checkbox-custom-label">Simple
                                                Type<span>170</span></label>
                                        </li>
                                        <li>
                                            <input id="t4" class="checkbox-custom" name="t4"
                                                type="checkbox">
                                            <label for="t4" class="checkbox-custom-label">Modern
                                                type<span>140</span></label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Single Option -->
            <div class="single_search_boxed">
                <div class="widget-boxed-header">
                    <h4><a href="#occation" data-toggle="collapse" class="collapsed" aria-expanded="false"
                            role="button">Occation</a></h4>
                </div>
                <div class="widget-boxed-body collapse" id="occation" data-parent="#occation">
                    <div class="side-list no-border">
                        <!-- Single Filter Card -->
                        <div class="single_filter_card">
                            <div class="card-body pt-0">
                                <div class="inner_widget_link">
                                    <ul class="no-ul-list">
                                        <li>
                                            <input id="o1" class="checkbox-custom" name="o1"
                                                type="checkbox">
                                            <label for="o1" class="checkbox-custom-label">All
                                                Occation<span>422</span></label>
                                        </li>
                                        <li>
                                            <input id="o2" class="checkbox-custom" name="o2"
                                                type="checkbox">
                                            <label for="o2" class="checkbox-custom-label">Normal
                                                Occation<span>472</span></label>
                                        </li>
                                        <li>
                                            <input id="t33" class="checkbox-custom" name="o33"
                                                type="checkbox">
                                            <label for="t33" class="checkbox-custom-label">Winter
                                                Occation<span>170</span></label>
                                        </li>
                                        <li>
                                            <input id="o4" class="checkbox-custom" name="o4"
                                                type="checkbox">
                                            <label for="o4" class="checkbox-custom-label">Summer
                                                Occation<span>140</span></label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Single Option -->
            <div class="single_search_boxed">
                <div class="widget-boxed-header">
                    <h4><a href="#colors" data-toggle="collapse" class="collapsed" aria-expanded="false"
                            role="button">Colors</a></h4>
                </div>
                <div class="widget-boxed-body collapse" id="colors" data-parent="#colors">
                    <div class="side-list no-border">
                        <!-- Single Filter Card -->
                        <div class="single_filter_card">
                            <div class="card-body pt-0">
                                <div class="text-left">
                                    <div class="form-check form-option form-check-inline mb-1">
                                        <input class="form-check-input" type="radio" name="colora8"
                                            id="whitea8">
                                        <label class="form-option-label rounded-circle" for="whitea8"><span
                                                class="form-option-color rounded-circle blc7"></span></label>
                                    </div>
                                    <div class="form-check form-option form-check-inline mb-1">
                                        <input class="form-check-input" type="radio" name="colora8"
                                            id="bluea8">
                                        <label class="form-option-label rounded-circle" for="bluea8"><span
                                                class="form-option-color rounded-circle blc2"></span></label>
                                    </div>
                                    <div class="form-check form-option form-check-inline mb-1">
                                        <input class="form-check-input" type="radio" name="colora8"
                                            id="yellowa8">
                                        <label class="form-option-label rounded-circle" for="yellowa8"><span
                                                class="form-option-color rounded-circle blc5"></span></label>
                                    </div>
                                    <div class="form-check form-option form-check-inline mb-1">
                                        <input class="form-check-input" type="radio" name="colora8"
                                            id="pinka8">
                                        <label class="form-option-label rounded-circle" for="pinka8"><span
                                                class="form-option-color rounded-circle blc3"></span></label>
                                    </div>
                                    <div class="form-check form-option form-check-inline mb-1">
                                        <input class="form-check-input" type="radio" name="colora8"
                                            id="reda">
                                        <label class="form-option-label rounded-circle" for="reda"><span
                                                class="form-option-color rounded-circle blc4"></span></label>
                                    </div>
                                    <div class="form-check form-option form-check-inline mb-1">
                                        <input class="form-check-input" type="radio" name="colora8"
                                            id="greena">
                                        <label class="form-option-label rounded-circle" for="greena"><span
                                                class="form-option-color rounded-circle blc6"></span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
