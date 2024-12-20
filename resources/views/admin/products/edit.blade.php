@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <form action="{{ route('products.update', $product->id) }}" method="post" id="productForm" name="productForm"
            enctype="multipart/form-data">
            @csrf
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" class="form-control"
                                                placeholder="Title" value="{{ $product->title }}">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="slug">Slug</label>
                                            <input type="text" name="slug" id="slug" class="form-control"
                                                placeholder="Slug" value="{{ $product->slug }}">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote"
                                                placeholder="Description">{{ $product->description }}</textarea>
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image">Product Images</label>
                            <input type="file" name="image[]" id="image" class="form-control" multiple>
                            @error('images.*')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row" id="product-images">

                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="text" name="price" id="price" class="form-control"
                                                placeholder="Price" value="{{ $product->price }}">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Original Price</label>
                                            <input type="text" name="compare_price" id="compare_price"
                                                class="form-control" placeholder="Compare Price"
                                                value="{{ $product->compare_price }}">
                                            <p class="text-muted mt-3">
                                                To show a reduced price, move the product’s original price here.
                                                Enter a lower value into Price.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventory</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text" name="sku" id="sku" class="form-control"
                                                placeholder="sku" value="{{ $product->sku }}">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" id="barcode" class="form-control"
                                                placeholder="Barcode" value="{{ $product->barcode }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="track_qty" value="No">
                                                <input class="custom-control-input" type="checkbox" id="track_qty"
                                                    value="Yes" {{ $product->track_qty == 'Yes' ? 'checked' : '' }}
                                                    name="track_qty" checked>
                                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" min="0" name="qty" id="qty"
                                                class="form-control" placeholder="Qty" value="{{ $product->qty }}">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option {{ $product->status == 1 ? 'selected' : '' }} value="1">Active
                                        </option>
                                        <option {{ $product->status == 0 ? 'selected' : '' }} value="0">Block
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h2 class="h4  mb-3">Product category</h2>
                                <div class="mb-3">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a Category</option>

                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option {{ $product->category_id == $category->id ? 'selected' : '' }}
                                                    value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="error"></p>
                                </div>

                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product brand</h2>
                                <div class="mb-3">
                                    <select name="brand" id="brand" class="form-control">
                                        <option value="">Select a brand</option>

                                        @if ($brands->isNotEmpty())
                                            @foreach ($brands as $brand)
                                                <option {{ $product->brand_id == $brand->id ? 'selected' : '' }}
                                                    value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        @endif

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Featured product</h2>
                                <div class="mb-3">
                                    <select name="is_featured" id="is_featured" class="form-control">
                                        <option {{ $product->is_featured == 'No' ? 'selected' : '' }} value="No">No
                                        </option>
                                        <option {{ $product->is_featured == 'Yes' ? 'selected' : '' }} value="Yes">
                                            Yes</option>
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Length</h2>
                                <input type="text" name="length" id="length" class="form-control"
                                    placeholder="Length" value="{{ $product->length }}">
                                <br>
                                <h2 class="h4 mb-3">Breath</h2>
                                <input type="text" name="breath" id="breath" class="form-control"
                                    placeholder="Breath" value="{{ $product->breath }}">
                                <br>
                                <h2 class="h4 mb-3">Height</h2>
                                <input type="text" name="height" id="height" class="form-control"
                                    placeholder="Height" value="{{ $product->height }}">
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Tax Type</h2>
                                @if ($tax_type !== 'no_tax')
                                    <label for="tax_price">Tax Price</label>
                                    <input type="text" name="tax_price" id="tax_price" class="form-control" placeholder="Tax Price" value="{{ old('tax_price', $product->tax_price ?? '') }}">
                                    <small class="form-text text-muted">
                                        Tax type: {{ ucfirst($tax_type) }}
                                    </small>
                                @else
                                    <p>No tax applied</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product Attributes</h2>
                                <div id="attributes-container">
                                    <!-- Populate Existing Attributes if in Edit Mode -->
                                    @foreach ($product->attributes as $index => $productAttribute)
                                        <div class="mb-3 attribute-row">
                                            <label class="form-label">Attribute Name</label>
                                            <select name="attribute_name[]" class="form-control attribute-select"
                                                onchange="showAttributeValues(this)">
                                                <option value="">Select Attribute</option>
                                                @foreach ($attributes as $attribute)
                                                    <option value="{{ $attribute->id }}"
                                                        {{ $productAttribute->id == $attribute->id ? 'selected' : '' }}>
                                                        {{ $attribute->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <div class="mt-3">
                                                <label class="form-label">Attribute Values</label>
                                                <select name="attribute_value[]" class="form-control attribute-values">
                                                    <option value="">Select a value</option>
                                                    @if ($productAttribute->values)
                                                        @foreach ($productAttribute->values as $value)
                                                            <option value="{{ $value->value }}"
                                                                {{ $value->value == $productAttribute->pivot->value ? 'selected' : '' }}>
                                                                {{ $value->value }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Default Attribute Row for Adding New Attributes -->
                                    <div class="mb-3 attribute-row">
                                        <label class="form-label">Attribute Name</label>
                                        <select name="attribute_name[]" class="form-control attribute-select"
                                            onchange="showAttributeValues(this)">
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
                                <button type="button" class="btn btn-outline-primary" onclick="addAttribute()">Add More
                                    Attributes</button>
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
                            }
                        </script>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
