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
            <div class="single_search_boxed">
                <div class="widget-boxed-header">
                    <h4><a href="#pricing" data-toggle="collapse" aria-expanded="false"
                            role="button">Pricing</a></h4>
                </div>
                <div class="widget-boxed-body collapse show" id="pricing" data-parent="#pricing">
                    <div class="side-list no-border mb-4">
                        <div class="rg-slider">
                            <input type="text" class="js-range-slider" name="my_range" value="" />
                        </div>
                    </div>
                </div>
            </div>

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
                                        <input class="form-check-input" type="radio" name="sizes" id="38s">
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
