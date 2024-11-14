@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Menu</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="{{ route('admin.menus.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="text" name="url" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="parent_id">Parent Menu</label>
                    <select name="parent_id" class="form-control">
                        <option value="">No Parent</option>
                        {{-- @foreach ($menus as $menu)
                            <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                        @endforeach --}}
                        @foreach ($menus as $menu)
                            <option value="{{ $menu->id }}">
                                {!! str_repeat('&mdash;', $menu->depth) !!} {{ $menu->name }}
                            </option>
                            @if ($menu->children->isNotEmpty())
                                @foreach ($menu->children as $child)
                                    <option value="{{ $child->id }}">
                                        {!! str_repeat('&mdash;', $child->depth) !!} {{ $child->name }}
                                    </option>
                                @endforeach        
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="order">Order</label>
                    <input type="number" name="order" class="form-control">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="location">Menu Location</label>
                    <select name="location" class="form-control">
                        <option value="header">Header</option>
                        <option value="footer">Footer</option>
                        <option value="both">Both</option>
                    </select>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>

        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
