@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Store</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('stores.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('stores.store') }}" method="POST" id="createStoreForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- Store Name -->
                            <div class="col-md-6 mb-3">
                                <label for="store_name" class="form-label">Store Name</label>
                                <input type="text" name="store_name" id="store_name" 
                                       class="form-control @error('store_name') is-invalid @enderror"
                                       value="{{ old('store_name') }}" placeholder="Enter store name">
                                @error('store_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="col-md-6 mb-3">
                                <label for="store_address" class="form-label">Address</label>
                                <input type="text" name="store_address" id="store_address" 
                                       class="form-control @error('store_address') is-invalid @enderror"
                                       value="{{ old('store_address') }}" placeholder="Enter address">
                                @error('store_address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- City -->
                            <div class="col-md-6 mb-3">
                                <label for="store_city" class="form-label">City</label>
                                <input type="text" name="store_city" id="store_city" 
                                       class="form-control @error('store_city') is-invalid @enderror"
                                       value="{{ old('store_city') }}" placeholder="Enter city">
                                @error('store_city')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- State -->
                            <div class="col-md-6 mb-3">
                                <label for="store_state" class="form-label">State</label>
                                <input type="text" name="store_state" id="store_state" 
                                       class="form-control @error('store_state') is-invalid @enderror"
                                       value="{{ old('store_state') }}" placeholder="Enter state">
                                @error('store_state')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Pin -->
                            <div class="col-md-6 mb-3">
                                <label for="store_pin" class="form-label">Pin</label>
                                <input type="text" name="store_pin" id="store_pin" 
                                       class="form-control @error('store_pin') is-invalid @enderror"
                                       value="{{ old('store_pin') }}" placeholder="Enter pin code">
                                @error('store_pin')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Country -->
                            <div class="col-md-6 mb-3">
                                <label for="store_country" class="form-label">Country</label>
                                <input type="text" name="store_country" id="store_country" 
                                       class="form-control @error('store_country') is-invalid @enderror"
                                       value="{{ old('store_country') }}" placeholder="Enter country">
                                @error('store_country')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label for="store_phone" class="form-label">Phone</label>
                                <input type="text" name="store_phone" id="store_phone" 
                                       class="form-control @error('store_phone') is-invalid @enderror"
                                       value="{{ old('store_phone') }}" placeholder="Enter phone number">
                                @error('store_phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- GST Number -->
                            <div class="col-md-6 mb-3">
                                <label for="gst_number" class="form-label">GST Number</label>
                                <input type="text" name="gst_number" id="gst_number" 
                                       class="form-control @error('gst_number') is-invalid @enderror"
                                       value="{{ old('gst_number') }}" placeholder="Enter GST number">
                                @error('gst_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Tax Type -->
                            <div class="col-md-6 mb-3">
                                <label for="tax_type" class="form-label">Tax Type</label>
                                <select name="tax_type" id="tax_type" 
                                        class="form-control @error('tax_type') is-invalid @enderror">
                                    <option value="no_tax" {{ old('tax_type') == 'no_tax' ? 'selected' : '' }}>No Tax</option>
                                    <option value="inclusive" {{ old('tax_type') == 'inclusive' ? 'selected' : '' }}>Inclusive</option>
                                    <option value="exclusive" {{ old('tax_type') == 'exclusive' ? 'selected' : '' }}>Exclusive</option>
                                </select>
                                @error('tax_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('stores.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
    </section>
@endsection
