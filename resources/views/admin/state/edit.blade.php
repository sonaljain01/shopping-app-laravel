@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit State</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('state.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('state.update', $state->id) }}" name="editStateForm" id="editStateForm" method="POST">
                @csrf
                @method('PUT') <!-- Laravel directive to use PUT method for update -->

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- City Name Input -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">State Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="State Name" value="{{ old('name', $state->name) }}" required>
                                </div>
                            </div>

                          
                            
                        </div>

                        
                    </div>
                </div>

                <!-- Submit and Cancel Buttons -->
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('state.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
    </section>
@endsection
