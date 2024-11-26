@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Pickup Address</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('pickup.index') }}" class="btn btn-primary">Back to Address List</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @include('admin.message')
        <div class="card">
            <div class="card-body">
                <form action="{{ route('pickup.update', $pickup->id) }}" name="editPickupForm" id="editPickupForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $pickup->name) }}" required>
                        @error('name') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address_1">Address</label>
                        <textarea name="address_1" id="address_1" class="form-control" required>{{ old('address_1', $pickup->address_1) }}</textarea>
                        @error('address_1') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address_2">Landmark</label>
                        <textarea name="address_2" id="address_2" class="form-control" required>{{ old('address_2', $pickup->address_2) }}</textarea>
                        @error('address_2') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $pickup->city) }}" required>
                        @error('city') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" name="state" id="state" class="form-control" value="{{ old('state', $pickup->state) }}" required>
                        @error('state') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" name="country" id="country" class="form-control" value="{{ old('country', $pickup->country) }}" required>
                        @error('country') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="zip">Zip</label>
                        <input type="text" name="zip" id="zip" class="form-control" value="{{ old('zip', $pickup->zip) }}" required>
                        @error('zip') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $pickup->phone) }}" required>
                        @error('phone') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tags">Tag</label>
                        <input type="text" name="tags" id="tags" class="form-control" value="{{ old('tags', $pickup->tags) }}">
                        @error('tags') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="is_default">Set as Default</label>
                        <div class="form-check">
                            <input type="checkbox" name="is_default" id="is_default" class="form-check-input" value="1" {{ old('is_default', $pickup->is_default) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">Make this the default pickup address</label>
                        </div>
                        @error('is_default') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Update Address</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
