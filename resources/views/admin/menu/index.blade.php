@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Menu Management</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">New Menu</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>

    {{-- <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @include('admin.message')
            <div class="card">
                <form action="" method="get">
                    <div class="card-header">
                        <div class="card-title">
                            <button type="button" onclick="window.location.href='{{ route('admin.menus.index') }}'"
                                class="btn btn-default btn-sm">Reset</button>
                        </div>
                        <div class="card-tools">
                            <div class="input-group input-group" style="width: 250px;">
                                <input value="{{ Request::get('keyword') }}" type="text" name="keyword"
                                    class="form-control float-right" placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>URL</th>
                                <th>Parent</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menus as $menu)
                                <tr>
                                    <td>{{ $menu->name }}</td>
                                    <td>{{ $menu->url }}</td>
                                    <td>{{ $menu->parent ? $menu->parent->name : 'None' }}</td>
                                    <td>{{ $menu->location }}</td>
                                    <td>
                                        @if ($menu->status == 1)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $menus->links() }}
                </div>
            </div>
        </div>
    </section> --}}
    {{-- <section class="content">
        <div class="container-fluid">
            @include('admin.message')
            <div class="card">
                <form action="" method="get">
                    <div class="card-header">
                        <div class="card-title">
                            <button type="button" onclick="window.location.href='{{ route('admin.menus.index') }}'"
                                class="btn btn-default btn-sm">Reset</button>
                        </div>
                        <div class="card-tools">
                            <div class="input-group input-group" style="width: 250px;">
                                <input value="{{ Request::get('keyword') }}" type="text" name="keyword"
                                    class="form-control float-right" placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
                <div class="card-body table-responsive p-0">
                    <ul id="menuList" class="list-group">
                        @foreach ($menus as $menu)
                        
                            <li class="list-group-item" data-id="{{ $menu->id }}">
                                {{ $menu->name }}
                                
                                @if ($menu->children->count())
                                    <ul class="list-group mt-2">
                                        @foreach ($menu->children as $child)
                                            <li class="list-group-item" data-id="{{ $child->id }}">
                                                {{ $child->name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                <br>
                                <td>parent: {{ $menu->parent ? $menu->parent->name : 'None' }}</td>
                                <br>
                                <td>URL: {{ $menu->url }}</td>
                                <br>
                                <td>Status: 
                                    @if ($menu->status == 1)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <br>
                                <td>Actions: 
                                    <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
    
                                </td>
                            </li>
                            
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section> --}}
    <section class="content">
        <div class="container-fluid">
            @include('admin.message')
            <div class="card">
                <form action="" method="get">
                    <div class="card-header">
                        <div class="card-title">
                            <button type="button" onclick="window.location.href='{{ route('admin.menus.index') }}'"
                                class="btn btn-default btn-sm">Reset</button>
                        </div>
                        <div class="card-tools">
                            <div class="input-group input-group" style="width: 250px;">
                                <input value="{{ Request::get('keyword') }}" type="text" name="keyword"
                                    class="form-control float-right" placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card-body table-responsive p-0">
                    <ul id="menuList" class="list-group">
                        @foreach ($menus as $menu)
                            <li class="list-group-item" data-id="{{ $menu->id }}">
                                <!-- Draggable area: Only the name -->
                                <div class="draggable-handle">
                                    {{ $menu->name }}
                                </div>
                                @if ($menu->children->count())
                                    <ul class="list-group mt-2">
                                        @foreach ($menu->children as $child)
                                            <li class="list-group-item" data-id="{{ $child->id }}">
                                                <div class="draggable-handle">
                                                    {{ $child->name }}
                                                </div>
                                                <div class="menu-details">
                                                    <!-- Optionally include more details for children -->
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif


                                <!-- Static non-draggable details -->
                                <div class="menu-details">
                                    <br>
                                    <span>Parent: {{ $menu->parent ? $menu->parent->name : 'None' }}</span>
                                    <br>
                                    <span>URL: {{ $menu->url }}</span>
                                    <br>
                                    <span>Status:
                                        @if ($menu->status == 1)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </span>
                                    <br>
                                    <span>Actions:</span>
                                    <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>


                            </li>
                        @endforeach
                        <h5>All Child Menus</h5>
                        @foreach ($menus as $menu)
                            @if ($menu->children->count())
                                @foreach ($menu->children as $child)
                                    <li class="list-group-item" data-id="{{ $child->id }}">
                                        <div class="draggable-handle">
                                            {{ $child->name }} (Child of {{ $menu->name }})
                                        </div>
                                        <div class="menu-details">
                                            <br>
                                            <span>Parent: {{ $menu->name }}</span>
                                            <br>
                                            <span>URL: {{ $child->url }}</span>
                                            <br>
                                            <span>Status:
                                                @if ($child->status == 1)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </span>
                                            <br>
                                            <span>Actions:</span>
                                            <a href="{{ route('admin.menus.edit', $child) }}"
                                                class="btn btn-warning">Edit</a>
                                            <form action="{{ route('admin.menus.destroy', $child) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        @endforeach
                    </ul>
                </div>
                
            </div>
        </div>
       
    </section>

    <!-- Include Sortable.js library -->
@endsection

@section('customJs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Sortable(document.getElementById('menuList'), {
                handle: '.draggable-handle',
                animation: 150,
                onEnd: function(evt) {
                    let order = [];
                    document.querySelectorAll('#menuList > li').forEach((item, index) => {
                        order.push({
                            id: item.getAttribute('data-id'),
                            position: index + 1
                        });
                    });

                    fetch('{{ route('admin.menus.updateOrder') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                order: order
                            })
                        }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                            } else {
                                alert('Failed to update menu order.');
                            }
                        });
                }
            });
        });
    </script>
@endsection
