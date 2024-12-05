@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Translation</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('translations.update', $translation->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="key">Translation Key</label>
            <input type="text" name="key" id="key" class="form-control" value="{{ $translation->key }}" required>
        </div>

        <div class="form-group">
            <label for="value">Translation Value</label>
            <textarea name="value" id="value" class="form-control" rows="4" required>{{ $translation->value }}</textarea>
        </div>

        <div class="form-group">
            <label for="language">Language</label>
            <select name="language" id="language" class="form-control" required>
                <option value="en" {{ $translation->language == 'en' ? 'selected' : '' }}>English</option>
                <option value="zh" {{ $translation->language == 'zh' ? 'selected' : '' }}>Chinese</option>
                <option value="hi" {{ $translation->language == 'hi' ? 'selected' : '' }}>Hindi</option>
                <!-- Add more languages as needed -->
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Translation</button>
        <a href="{{ route('translations.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection