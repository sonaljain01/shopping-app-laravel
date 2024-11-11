@extends('admin.layouts.app')

@section('content')
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <form action="{{ route('products.store') }}" method="POST" id="productForm" enctype="multipart/form-data">
            @csrf
            <div class="container-fluid">
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <!-- Title & Slug -->
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" id="title" class="form-control"
                                            placeholder="Title">
                                        @error('title')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="slug">Slug</label>
                                        <input type="text" name="slug" id="slug" class="form-control"
                                            placeholder="Slug">
                                        @error('slug')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <script>
                                    document.getElementById('title').addEventListener('input', function() {
                                        let title = this.value;
                                        let slug = title.toLowerCase().trim()
                                            .replace(/[^a-z0-9\s-]/g, '') // Remove invalid characters
                                            .replace(/\s+/g, '-') // Replace spaces with dashes
                                            .replace(/-+/g, '-'); // Replace multiple dashes with a single dash

                                        document.getElementById('slug').value = slug;
                                    });
                                </script>

                                <!-- Description -->
                                <div class="col-md-12 mb-3">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control summernote" placeholder="Description"></textarea>
                                    @error('description')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Product Images -->
                        <div class="form-group">
                            <label for="image">Product Images</label>
                            <input type="file" name="image[]" id="image" class="form-control" multiple>
                            @error('images.*')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row" id="product-images"></div>

                        <!-- Pricing -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="price">Price</label>
                                        <input type="text" name="price" id="price" class="form-control"
                                            placeholder="Price">
                                        @error('price')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="compare_price">Original Price</label>
                                        <input type="text" name="compare_price" id="compare_price" class="form-control"
                                            placeholder="Compare Price">
                                        <small class="text-muted mt-3">To show a reduced price, move the product’s original
                                            price here.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Inventory -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventory</h2>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="sku">SKU (Stock Keeping Unit)</label>
                                        <input type="text" name="sku" id="sku" class="form-control"
                                            placeholder="SKU">
                                        @error('sku')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="barcode">Barcode</label>
                                        <input type="text" name="barcode" id="barcode" class="form-control"
                                            placeholder="Barcode">
                                    </div>

                                    <div class="col-md-12">
                                        <div class="custom-control custom-checkbox">
                                            <input type="hidden" name="track_qty" value="No">
                                            <input class="custom-control-input" type="checkbox" id="track_qty"
                                                value="Yes" name="track_qty" checked>
                                            <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                        </div>
                                        @error('track_qty')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror

                                        <input type="number" min="0" name="qty" id="qty"
                                            class="form-control" placeholder="Qty">
                                        @error('qty')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-4">
                        <!-- Product Status -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product Status</h2>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Blocked</option>
                                </select>
                            </div>
                        </div>

                        <!-- Product Category -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product Category</h2>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select a Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Product Brand -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product Brand</h2>
                                <select name="brand" id="brand" class="form-control">
                                    <option value="">Select a Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Featured Product -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Featured Product</h2>
                                <select name="is_featured" id="is_featured" class="form-control">
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                        </div>

                        <!-- Product Attributes -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product Attributes</h2>
                                <div id="attributes-container">
                                    <!-- Default Attribute Form -->
                                    <div class="mb-3 attribute-row">
                                        <label class="form-label">Attribute Name</label>
                                        <select name="attribute_name[]" class="form-control attribute-select" onchange="showAttributeValues(this)">
                                            <option value="">Select Attribute</option>
                                            @foreach ($attributes as $attribute)
                                                <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                            @endforeach
                                        </select>
                        
                                        <div class="mt-3">
                                            <label class="form-label">Attribute Values</label>
                                            <select name="attribute_value[]" class="form-control attribute-values">
                                                <option value="">Select a value</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary" onclick="addAttribute()">Add More Attributes</button>
                            </div>
                        </div>
                        
                        <!-- Preload attribute values in a hidden format -->
                        @foreach ($attributes as $attribute)
                            <div id="attribute-{{ $attribute->id }}" class="d-none">
                                @foreach ($attribute->values as $value)
                                    <span class="attribute-value">{{ $value->value }}</span>
                                @endforeach
                            </div>
                        @endforeach
                        
                        <script>
                            // Show values for the selected attribute
                            function showAttributeValues(selectElement) {
                                const attributeId = selectElement.value;
                                const valuesSelect = selectElement.closest('.attribute-row').querySelector('.attribute-values');
                        
                                valuesSelect.innerHTML = '<option value="">Select a value</option>'; // Clear existing options
                        
                                if (attributeId) {
                                    const valuesContainer = document.getElementById(`attribute-${attributeId}`);
                                    if (valuesContainer) {
                                        const values = valuesContainer.querySelectorAll('.attribute-value');
                                        values.forEach(value => {
                                            const option = document.createElement('option');
                                            option.value = value.textContent;
                                            option.textContent = value.textContent;
                                            valuesSelect.appendChild(option);
                                        });
                                    }
                                }
                            }
                        
                            // Add new attribute row
                            function addAttribute() {
                                const container = document.getElementById('attributes-container');
                        
                                // Clone the first attribute row
                                const newRow = container.querySelector('.attribute-row').cloneNode(true);
                        
                                // Clear the selected options of the cloned row
                                newRow.querySelector('.attribute-select').value = '';
                                newRow.querySelector('.attribute-values').innerHTML = '<option value="">Select a value</option>';
                        
                                // Append the cloned row to the container
                                container.appendChild(newRow);
                        
                                // Optionally, you can also attach event listeners to the new select elements
                            }
                        </script>
                        
                        
                        
                       

                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </section>
@endsection
