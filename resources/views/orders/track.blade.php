{{-- resources/views/orders/track.blade.php --}}
@extends('front.layouts.app')

@section('content')
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
