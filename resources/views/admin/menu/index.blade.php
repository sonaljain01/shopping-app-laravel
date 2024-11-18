@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <h1>Menu Management</h1>
</section>

<section class="content">
    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.menus.create') }}" class="btn btn-success">Add Menu</a>
        </div>
        <div class="card-body">
            <ul id="menuList" class="list-group">
                @foreach ($menus as $menu)
                    <li class="list-group-item" data-id="{{ $menu->id }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="draggable-handle">{{ $menu->name }}</span>
                            <div>
                                <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </div>

                        <!-- Nested child menus -->
                        @if ($menu->children->count())
                            <ul class="nested-sortable list-group mt-2">
                                @foreach ($menu->children as $child)
                                    <li class="list-group-item" data-id="{{ $child->id }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="draggable-handle">{{ $child->name }}</span>
                                            <div>
                                                <a href="{{ route('admin.menus.edit', $child) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('admin.menus.destroy', $child) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuList = document.getElementById('menuList');

        // Initialize Sortable for parent list
        Sortable.create(menuList, {
            handle: '.draggable-handle',
            group: 'nested',
            animation: 150,
            onEnd: updateOrder
        });

        // Initialize Sortable for nested child lists
        document.querySelectorAll('.nested-sortable').forEach(function (nestedList) {
            Sortable.create(nestedList, {
                handle: '.draggable-handle',
                group: 'nested',
                animation: 150,
                onEnd: updateOrder
            });
        });

        // Function to update order and parent-child relationships
        function updateOrder() {
            const orderData = [];
            document.querySelectorAll('#menuList > .list-group-item').forEach((item, index) => {
                orderData.push({
                    id: item.dataset.id,
                    parent_id: null, // Root level item
                    order: index
                });

                const children = item.querySelectorAll('.nested-sortable > .list-group-item');
                children.forEach((child, childIndex) => {
                    orderData.push({
                        id: child.dataset.id,
                        parent_id: item.dataset.id, // Parent ID
                        order: childIndex
                    });
                });
            });

            // Send AJAX request to save changes
            fetch('{{ route('admin.menus.updateOrder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order: orderData })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                } else {
                    alert('Failed to update menu order');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
</script>
@endsection
