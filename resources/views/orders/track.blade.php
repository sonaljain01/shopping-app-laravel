{{-- resources/views/orders/track.blade.php --}}
@extends('front.layouts.app')

@section('content')
<div class="gray py-3">
    <div class="container">
        <div class="row">
            <div class="colxl-12 col-lg-12 col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('front.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Support</a></li>
                        <li class="breadcrumb-item active" aria-current="page">TrackcOrders</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <h2>Track Your Order</h2>
    <form action="{{ route('track.orders') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="order_id">Enter Order ID</label>
            <input type="text" name="order_id" id="order_id" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Track Order</button>
    </form>
</div>
@endsection
