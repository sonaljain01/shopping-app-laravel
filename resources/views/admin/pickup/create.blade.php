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
        @include('admin.message') <!-- Message partial for alerts -->

        <div class="card">
            <div class="card-body">
                <form action="{{ route('pickup.store') }}" method="POST">
                    @csrf

                    <!-- Name Field -->
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            class="form-control" 
                            value="{{ old('name') }}" 
                            placeholder="Enter name" 
                            required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="form-control" 
                            value="{{ old('email') }}" 
                            placeholder="Enter email" 
                            required>
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Address Field -->
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea 
                            name="address" 
                            id="address_1" 
                            rows="3" 
                            class="form-control" 
                            placeholder="Enter address" 
                            required>{{ old('address') }}</textarea>
                        @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Location Fields -->
                    <div class="form-row">
                        @foreach (['city' => 'City', 'state' => 'State', 'country' => 'Country'] as $field => $label)
                            <div class="form-group col-md-4">
                                <label for="{{ $field }}">{{ $label }}</label>
                                <input 
                                    type="text" 
                                    name="{{ $field }}" 
                                    id="{{ $field }}" 
                                    class="form-control" 
                                    value="{{ old($field) }}" 
                                    placeholder="Enter {{ strtolower($label) }}" 
                                    required>
                                @error($field) <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        @endforeach

                        <!-- Pincode Field -->
                        <div class="form-group col-md-4">
                            <label for="pincode">Pincode</label>
                            <input 
                                type="text" 
                                name="pincode" 
                                id="pincode" 
                                class="form-control" 
                                value="{{ old('pincode') }}" 
                                placeholder="Enter pincode" 
                                required>
                            @error('pincode') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <!-- Phone Field -->
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input 
                            type="text" 
                            name="phone" 
                            id="phone" 
                            class="form-control" 
                            value="{{ old('phone') }}" 
                            placeholder="Enter phone number" 
                            required>
                        @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Tag Field -->
                    <div class="form-group">
                        <label for="tag">Tag (Optional)</label>
                        <input 
                            type="text" 
                            name="tag" 
                            id="tags" 
                            class="form-control" 
                            value="{{ old('tag') }}" 
                            placeholder="e.g., Main Office, Warehouse">
                        @error('tag') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Default Pickup Checkbox -->
                    <div class="form-group">
                        <label for="is_default">Set as Default</label>
                        <div class="form-check">
                            <input 
                                type="checkbox" 
                                name="is_default" 
                                id="is_default" 
                                class="form-check-input" 
                                value="1" 
                                {{ old('is_default') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">Make this the default pickup address</label>
                        </div>
                        @error('is_default') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Action Buttons -->
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
