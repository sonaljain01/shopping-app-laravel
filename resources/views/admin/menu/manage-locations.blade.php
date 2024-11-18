@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <h1>Manage Menu Locations</h1>
</section>

<section class="content">
    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Back to Menu Management</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.menus.updateLocations') }}" method="POST">
                @csrf
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Menu Name</th>
                            <th>Current Location</th>
                            <th>Update Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menus as $menu)
                        <tr>
                            <td>{{ $menu->name }}</td>
                            <td>{{ ucfirst($menu->location) }}</td>
                            <td>
                                <select name="locations[{{ $menu->id }}]" class="form-control">
                                    <option value="header" {{ $menu->location == 'header' ? 'selected' : '' }}>Header</option>
                                    <option value="footer" {{ $menu->location == 'footer' ? 'selected' : '' }}>Footer</option>
                                    <option value="both" {{ $menu->location == 'both' ? 'selected' : '' }}>Both</option>
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
