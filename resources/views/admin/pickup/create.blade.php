@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Pickup Address</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('pickup.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @include('admin.message') <!-- Include message partial for alerts -->

        <div class="card">
            <div class="card-body">
                <form action="{{ route('pickup.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" 
                            value="{{ old('name') }}" placeholder="Enter name" required>
                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address_1">Address</label>
                        <textarea name="address_1" id="address_1" rows="3" class="form-control" 
                            placeholder="Enter address" required>{{ old('address_1') }}</textarea>
                        @error('address_1')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="address_2">Landmark</label>
                        <textarea name="address_2" id="address_2" rows="3" class="form-control" 
                            placeholder="Enter address" required>{{ old('address_2') }}</textarea>
                        @error('address_2')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="city">City</label>
                            <input type="text" name="city" id="city" class="form-control" 
                                value="{{ old('city') }}" placeholder="Enter city" required>
                            @error('city')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="state">State</label>
                            <input type="text" name="state" id="state" class="form-control" 
                                value="{{ old('state') }}" placeholder="Enter state" required>
                            @error('state')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="country">Country</label>
                            <input type="text" name="country" id="country" class="form-control" 
                                value="{{ old('country') }}" placeholder="Enter country" required>
                            @error('country')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="zip">ZIP Code</label>
                            <input type="text" name="zip" id="zip" class="form-control" 
                                value="{{ old('zip') }}" placeholder="Enter ZIP code" required>
                            @error('zip')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" 
                            value="{{ old('phone') }}" placeholder="Enter phone number" required>
                        @error('phone')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tags">Tag (Optional)</label>
                        <input type="text" name="tags" id="tags" class="form-control" 
                            value="{{ old('tags') }}" placeholder="e.g., Main Office, Warehouse">
                        @error('tags')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="is_default">Set as Default</label>
                        <div class="form-check">
                            <input type="checkbox" name="is_default" id="is_default" 
                                class="form-check-input" value="1" {{ old('is_default') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">Make this the default pickup address</label>
                        </div>
                        @error('is_default')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Save Address</button>
                        <a href="{{ route('pickup.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
