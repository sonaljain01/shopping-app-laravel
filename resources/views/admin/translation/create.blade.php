@extends('admin.layouts.app')

@section('content')
<div class="container">
    
    <h1 class="mb-4">Add Translation</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('translations.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="key">Translation Key</label>
            <input type="text" name="key" id="key" class="form-control" placeholder="Enter translation key" required value="{{ old('key') }}">
        </div>

        <div class="form-group">
            <label for="value">Translation Value</label>
            <textarea name="value" id="value" class="form-control" rows="4" placeholder="Enter translation value" value="{{ old('value') }}" required></textarea>
        </div>

        <div class="form-group">
            <label for="language">Language</label>
            <select name="language" id="language" class="form-control" required>
                <option value="en">English</option>
                <option value="zh">Chinese</option>
                <option value="hi">Hindi</option>
                
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Add Translation</button>
        <a href="{{ route('translations.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection