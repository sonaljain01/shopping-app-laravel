@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Brand</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('brands.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    {{-- <form method="POST" action="{{ route('attributes.update', $attribute->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Attribute Name:</label>
            <input type="text" name="name" id="name" class="form-control"
                value="{{ old('name', $attribute->name) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Attribute</button>
    </form>
    </div> --}}
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="{{ route('attributes.update', $attribute->id) }}" method="POST" id="createAttributeForm"
                name="createAttributeForm">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Attribute Name:</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ old('name', $attribute->name) }}" required>
                                    <p></p>
                                </div>
                            </div>


                            
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('attributes.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
    </section>
@endsection
