@extends('admin.layouts.app')

@section('content')

<div class="container">
    <h2>Edit Attribute</h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('attributes.update', $attribute->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Attribute Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $attribute->name) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Attribute</button>
    </form>
</div>

@endsection
