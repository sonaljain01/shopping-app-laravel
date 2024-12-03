@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Store</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('stores.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('stores.update', $store->id) }}" method="POST" id="editStoreForm">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="store_name">Store Name</label>
                                <input type="text" name="store_name" id="store_name" class="form-control"
                                    value="{{ old('store_name', $store->store_name) }}" placeholder="Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="store_address">Address</label>
                                <input type="text" name="store_address" id="store_address" class="form-control"
                                    value="{{ old('store_address', $store->store_address) }}" placeholder="Address">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="store_city">City</label>
                                <input type="text" name="store_city" id="store_city" class="form-control"
                                    value="{{ old('store_city', $store->store_city) }}" placeholder="City">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="store_state">State</label>
                                <input type="text" name="store_state" id="store_state" class="form-control"
                                    value="{{ old('store_state', $store->store_state) }}" placeholder="State">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="store_pin">Pin</label>
                                <input type="text" name="store_pin" id="store_pin" class="form-control"
                                    value="{{ old('store_pin', $store->store_pin) }}" placeholder="Pin">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="store_country">Country</label>
                                <input type="text" name="store_country" id="store_country" class="form-control"
                                    value="{{ old('store_country', $store->store_country) }}" placeholder="Country">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="store_phone">Phone</label>
                                <input type="text" name="store_phone" id="store_phone" class="form-control"
                                    value="{{ old('store_phone', $store->store_phone) }}" placeholder="Phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gst_number">GST Number</label>
                                <input type="text" name="gst_number" id="gst_number" class="form-control"
                                    value="{{ old('gst_number', $store->gst_number) }}" placeholder="GST Number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tax_type" class="form-label">Tax Type</label>
                                <select name="tax_type" id="tax_type" class="form-control">
                                    <option value="no_tax" {{ $store->tax_type == 'no_tax' ? 'selected' : '' }}>No Tax</option>
                                    <option value="inclusive" {{ $store->tax_type == 'inclusive' ? 'selected' : '' }}>Tax Inclusive</option>
                                    <option value="exclusive" {{ $store->tax_type == 'exclusive' ? 'selected' : '' }}>Tax Exclusive</option>
                                </select>
                                <p class="form-text">Please select the applicable tax type.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('stores.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
</section>
@endsection
