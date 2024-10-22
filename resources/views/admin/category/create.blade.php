{{-- DAshboard CHILD LAYOUT, parent layout - app.blade.php --}}

@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="{{ route('categories.store') }}" method="POST" id="categoryForm" name="categoryForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        placeholder="Slug" >
                                    <p></p>
                                </div>
                            </div>
                            <script>
                                document.getElementById('name').addEventListener('input', function() {
                                    let name = this.value;
                                    let slug = name.toLowerCase().trim()
                                        .replace(/[^a-z0-9\s-]/g, '') // Remove invalid characters
                                        .replace(/\s+/g, '-') // Replace spaces with dashes
                                        .replace(/-+/g, '-'); // Replace multiple dashes with a single dash

                                    document.getElementById('slug').value = slug;

                                });
                            </script>
                            
                            <div class="form-group">
                                <label for="parent_id">Parent Category (if sub-category)</label>
                                <select name="parent_id" id="parent_id" class="form-control">
                                    <option value="">None</option>
                                    @if(isset($categories) && $categories->isNotEmpty())
                                        @foreach ($categories as $parentCategory) <!-- Use a different variable name -->
                                            <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select type="text" name="status" id="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Block</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Show on Home</label>
                                    <select type="text" name="showHome" id="showHome" class="form-control">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
