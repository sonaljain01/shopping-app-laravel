@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Pickup Address</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('pickup.create') }}" class="btn btn-primary">New Address</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>

<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <div class="card">
            <form action="" method="get">
                <div class="card-header">
                    <div class="card-title">
                        <button type="button" onclick="window.location.href='{{ route('pickup.index') }}'"
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
                            <th>ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Zip</th>
                            <th>Phone</th>
                            <th>tag</th>
                            <th>is_default</th>
                            <th width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pickupAddresses as $pickup)
                            <tr>
                                <td>{{ $pickup->id }}</td>
                                <td>{{ $pickup->name }}</td>
                                <td>{{ $pickup->address_1 }}</td>
                                <td>{{ $pickup->city }}</td>
                                <td>{{ $pickup->state }}</td>
                                <td>{{ $pickup->zip }}</td>
                                <td>{{ $pickup->phone }}</td>
                                <td>{{ $pickup->tags }}</td>
                                <td>{{ $pickup->is_default }}</td>
                                <td>
                                    <a href="{{ route('pickup.edit', $pickup->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('pickup.destroy', $pickup->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Do you want to delete this address?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $pickupAddresses->links() }}
            </div>
        </div>
    </div>
</section>

@endsection
