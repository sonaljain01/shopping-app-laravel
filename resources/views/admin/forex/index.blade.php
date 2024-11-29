@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <h1>Manage Forex Rates</h1>
</section>

<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Forex</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('forex.create') }}" class="btn btn-primary">Add Rate</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <div class="card">
            <form action="" method="get">
                <div class="card-header">
                    <div class="card-title">
                        <button type="button" onclick="window.location.href='{{ route('admin.forex.index') }}'"
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
                            <th width="60">ID</th>
                            <th>Base Currency</th>
                            <th>Target Currency</th>
                            <th>Rate</th>
                            <th width="200">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($rates->isNotEmpty())
                            @foreach ($rates as $rate)
                                <tr>
                                    <td>{{ $rate->id }}</td>
                                    <td>{{ $rate->base_currency }}</td>
                                    <td>{{ $rate->target_currency }}</td>
                                    <td>{{ $rate->rate }}</td>
                                    
                                    <td>
                                        <a href="{{ route('forex.edit', $rate->id) }}">
                                            <svg class="filament-link-icon w-4 h-4 mr-1"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor" aria-hidden="true">
                                                <path
                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                </path>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('forex.destroy', $rate->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">No forex found</td>
                            </tr>
                        @endif


                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{-- {{ $brands->links() }} --}}

            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
@endsection