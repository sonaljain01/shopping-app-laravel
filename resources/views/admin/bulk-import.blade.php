{{-- resources/views/admin/bulk-import.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Bulk Import and Download Section</h2>
    
    <div class="mt-4">
        <h4>Download CSV Templates</h4>
        <ul>
            <li>Product template: <br><a href="{{ route('admin.products.template') }}">Download Product Template</a></li>
            <li>Categories template: <br><a href="{{ route('admin.products.categories') }}">Download Categories CSV</a></li>
            <li>Brand template: <br><a href="{{ route('admin.products.brands') }}">Download Brands CSV</a></li>
            <li>Attributes template: <br><a href="{{ route('admin.products.attributes') }}">Download Attributes CSV</a></li>
            <li>Attributes Values template: <br><a href="{{ route('admin.download.attribute-values') }}">Download Attribute Values CSV</a></li>

        </ul>
    </div>
    
    <div class="mt-4">
        <h4>Bulk Import Products</h4>
        <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Upload Product CSV:</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Import Products</button>
        </form>
    </div>
</div>
@endsection
