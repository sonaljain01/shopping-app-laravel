@extends('front.layouts.app')

@section('content')
    <div class="container">
        <div class="row align-items-start justify-content-between">
            @include('front.layouts.sidebar_two')

            <div class="col-md-9 text-right mt-5">
                <h1>{{ $customer->username }}'s Profile</h1>
                <p>Email: {{ $customer->email }}</p>
                <p>Status: {{ $customer->status ? 'Active' : 'Blocked' }}</p>

                <h2>Orders</h2>
                <ul>
                    @foreach ($customer->orders as $order)
                        <li>Order #{{ $order->id }} - {{ $order->status }}</li>
                    @endforeach
                </ul>

                <a href="{{ route('admin.restoreAdminSession') }}" class="btn btn-secondary">Return to Admin Mode</a>
            </div>
        </div>
    </div>
@endsection
