@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>ShipRocket Credentials</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="shiprocket_email" class="form-label">ShipRocket Username</label>
            <input type="email" name="shiprocket_email" id="shiprocket_email" class="form-control"
                   value="{{ old('shiprocket_username', $shiprocket_email) }}" required>
        </div>
        <div class="mb-3">
            <label for="shiprocket_password" class="form-label">ShipRocket Password</label>
            <input type="password" name="shiprocket_password" id="shiprocket_password" class="form-control"
                   value="{{ old('shiprocket_password', $shiprocket_password) }}" required>
        </div>
        <div class="mb-3">
            <label for="shiprocket_channel_id" class="form-label">ShipRocket Channel ID</label>
            <input type="text" name="shiprocket_channel_id" id="shiprocket_channel_id" class="form-control"
                   value="{{ old('shiprocket_channel_id', $shiprocket_channel_id) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
@endsection
