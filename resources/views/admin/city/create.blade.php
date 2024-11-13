{{-- @extends('admin.layouts.app')

@section('content')
    <h1>Add New City</h1>

    <form action="{{ route('city.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="city_name">City Name</label>
            <input type="text" name="city_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="pincode">Pincode</label>
            <input type="text" name="pincode" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
@endsection --}}


{{-- DAshboard CHILD LAYOUT, parent layout - app.blade.php --}}

@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create City</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('city.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="{{ route('city.store') }}" name="createCityForm" id="createCityForm" method="POST" >
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- City Name Input -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">City Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="City Name" required>
                                </div>
                            </div>
            
                            <!-- State Dropdown -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="state_id">State</label>
                                    <select name="state_id" id="state_id" class="form-control" required>
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
            
                        <div class="row">
                            <!-- Pincode Dropdown (or text input if you prefer manual entry) -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pincode">Pincode</label>
                                    <input type="text" name="pincode" id="pincode" class="form-control" placeholder="Enter Pincode" required>
                                </div>
                            </div>
            
                            <!-- City Status (Active/Blocked) -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_enabled">City Status</label>
                                    <select name="is_enabled" id="is_enabled" class="form-control" required>
                                        <option value="1">enabled</option>
                                        <option value="0">disabled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- Submit and Cancel Buttons -->
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('city.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
            
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection




