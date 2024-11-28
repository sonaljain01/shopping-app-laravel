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
        <div class="container-fluid">
            @include('admin.message')
            <div class="card">
                <form action="{{ route('pickup.index') }}" method="get">
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
                                <th>Address NickName</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Number</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($addresses as $address)
                                <tr>
                                    <td>{{ $address->tag }}</td>
                                    <td class="text-secondary">
                                        {{ $address->address }}, {{ $address->city }}, {{ $address->state }},
                                        {{ $address->pincode }}
                                    </td>
                                    <td class="text-secondary">
                                        {{ $address->phone }}
                                    </td>
                                    <td class="text-secondary"><a href="#"
                                            class="text-reset">{{ $address->email }}</a></td>
                                    <td class="d-flex" style="gap: 0.5rem;">
                                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal-update"
                                            id="btn-update" data-id="{{ $address->id }}" data-tag="{{ $address->tag }}"
                                            data-email="{{ $address->email }}" data-phone="{{ $address->phone }}"
                                            data-address="{{ $address->address }}" data-pincode="{{ $address->pincode }}"
                                            data-name="{{ $address->name }}" data-city="{{ $address->city }}"
                                            data-state="{{ $address->state }}"
                                            data-country="{{ $address->country }}">Edit</a>
                                        {{-- <a href="javascript:void(0)" class="text-danger" data-bs-toggle="modal"
                                    data-bs-target="#modal-delete-{{$address->id}}">Delete</a> --}}
                                    </td>
                                </tr>

                                <!-- delete model -->
                                <div class="modal modal-blur fade" id="modal-delete-{{ $address->id }}" tabindex="-1"
                                    role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                            <div class="modal-status bg-danger"></div>
                                            <div class="modal-body text-center py-4">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon mb-2 text-danger icon-lg" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" />
                                                    <path d="M12 9v4" />
                                                    <path d="M12 17h.01" />
                                                </svg>
                                                <h3>Are you sure?</h3>
                                                <div class="text-secondary">Do you really want to delete this Address? This
                                                    action cannot be undone.</div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="w-100">
                                                    <div class="row">
                                                        <div class="col"><a href="#" class="btn w-100"
                                                                data-bs-dismiss="modal">Cancel</a></div>
                                                        <div class="col">
                                                            <form id="delete-form-{{ $address->id }}"
                                                                action="{{ route('pickupaddress.delete', $address->id) }}"
                                                                method="POST" class="w-100">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger w-100">Delete
                                                                    Address</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $addresses->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
