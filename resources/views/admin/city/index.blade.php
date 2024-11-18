@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Cities</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('city.create') }}" class="btn btn-primary">New City</a>
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
                            <button type="button" onclick="window.location.href='{{ route('city.index') }}'"
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
                                <th>City</th>
                                <th>State</th>
                                <th>is_enabled</th>
                                <th width="100">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cities as $city)
                                <tr>
                                    <td>{{ $city->name }}</td>
                                    <td>{{ $city->state->name }}</td>
                                    <td>
                                        <span class="badge {{ $city->is_enabled ? 'badge-success' : 'badge-danger' }}">
                                            {{ $city->is_enabled ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('city.toggle', $city->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn {{ $city->is_enabled ? 'btn-danger' : 'btn-success' }}">
                                                {{ $city->is_enabled ? 'Disable Delivery' : 'Enable Delivery' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('city.edit', $city->id) }}">
                                            <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path
                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                </path>
                                            </svg>
                                        </a>
                                        <a href="#" onclick="deleteCity({{ $city->id }})"
                                            class="text-danger w-4 h-4 mr-1">
                                            <svg wire:loading.remove.delay="" wire:target=""
                                                class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path ath fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $cities->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection


@section('customJs')
    <script>
        function deleteCity(id) {
            var url = '{{ route('city.delete', 'ID') }}';
            var newUrl = url.replace("ID", id);
            if (confirm("Are you sure you want to delete this City?")) {
                $.ajax({
                    url: newUrl,
                    type: 'DELETE',
                    data: {},
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        if (response["status"]) {

                            window.location.href = "{{ route('city.index') }}"
                        }
                    }
                });
            }
        }
    </script>
@endsection