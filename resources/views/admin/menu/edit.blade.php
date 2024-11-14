@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Menu</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('admin.menus.update', $menu) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $menu->name }}" required>
                </div>
                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="text" name="url" class="form-control" value="{{ $menu->url }}" required>
                </div>
                <div class="form-group">
                    <label for="parent_id">Parent Menu</label>
                    <select name="parent_id" class="form-control">
                        <option value="">No Parent</option>
                       
                        @foreach ($menus as $menuItem)
                            <option value="{{ $menuItem->id }}"
                                @if ($menuItem->id == $menu->parent_id) selected @endif>
                                {!! str_repeat('&mdash;', $menuItem->depth) !!} {{ $menuItem->name }}
                            </option>
                            @if ($menuItem->children->isNotEmpty())
                                @foreach ($menuItem->children as $child)
                                    <option value="{{ $child->id }}"
                                        @if ($child->id == $menu->parent_id) selected @endif>
                                        {!! str_repeat('&mdash;', $child->depth) !!} {{ $child->name }}
                                    </option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="order">Order</label>
                    <input type="number" name="order" class="form-control" value="{{ $menu->order }}">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" class="form-control">
                        <option value="1" @if ($menu->status) selected @endif>Active</option>
                        <option value="0" @if (!$menu->status) selected @endif>Inactive</option>
                    </select>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
    </section>
@endsection
