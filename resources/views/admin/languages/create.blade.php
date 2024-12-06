@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h1>Add Language</h1>
        <div class="text-right">
            <a href="{{ route('languages.index') }}" class="btn btn-primary">Back</a>
        </div>
    </div>

    <div class="container-fluid ">
        <form action="{{ route('languages.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Code:</label>
                <input type="text" name="code" required>
            </div>
            <button type="submit">Add</button>
        </form>
    </div>
@endsection
