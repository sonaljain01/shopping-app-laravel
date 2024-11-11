@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $customer->username }}'s Profile</h1>
        <p>Email: {{ $customer->email }}</p>
        <p>Status: {{ $customer->status ? 'Active' : 'Blocked' }}</p>

        <h2>Orders</h2>
        <ul>
            @foreach($customer->orders as $order)
                <li>Order #{{ $order->id }} - {{ $order->status }}</li>
            @endforeach
        </ul>
        
        <a href="{{ route('admin.restoreAdminSession') }}" class="btn btn-secondary">Return to Admin Mode</a>
    </div>
@endsection
