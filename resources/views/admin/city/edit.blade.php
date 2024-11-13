@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit City</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('city.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('city.update', $city->id) }}" name="editCityForm" id="editCityForm" method="POST">
                @csrf
                @method('PUT') <!-- Laravel directive to use PUT method for update -->

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- City Name Input -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">City Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="City Name" value="{{ old('name', $city->name) }}" required>
                                </div>
                            </div>

                            <!-- State Dropdown -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="state_id">State</label>
                                    <select name="state_id" id="state_id" class="form-control" required>
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ $city->state_id == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- City Status (Enabled/Disabled) -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_enabled">City Status</label>
                                    <select name="is_enabled" id="is_enabled" class="form-control" required>
                                        <option value="1" {{ $city->is_enabled ? 'selected' : '' }}>Enabled</option>
                                        <option value="0" {{ !$city->is_enabled ? 'selected' : '' }}>Disabled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit and Cancel Buttons -->
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('city.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
    </section>
@endsection
