{{-- DAshboard CHILD LAYOUT, parent layout - app.blade.php --}}

@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit User</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('users.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="{{ route('users.update', $user->id) }}" method="POST" id="categoryForm" name="categoryForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input value="{{ $user->username }}" type="text" name="name" id="name" class="form-control"
                                        placeholder="Name">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Email</label>
                                    <input value="{{ $user->email }}" type="text" name="email" id="email" class="form-control"
                                        placeholder="Email" >
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password">Password</label>
                                    <input type="text" name="password" id="password" class="form-control"
                                        placeholder="Password" >
                                        <span>To change password please enter a value, otherwiese leave blank</span>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{  $user->status == 1 ? 'selected' : '' }} value="1">Active</option>
                                        <option {{  $user->status == 0 ? 'selected' : '' }} value="0">Block</option>
                                    </select>
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

                                    

                                });
                            </script>
                            
                           
  
                            
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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
