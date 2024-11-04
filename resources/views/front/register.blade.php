@extends('front.layouts.app')

@section('content')
<div class="container">
    <div class="row align-items-start justify-content-between">
        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 fluid">
            <form action="{{ route('front.register.store') }}" method="POST" class="border p-3 rounded">
                @csrf
                <div class="form-group">
                    <label for="username">User Name *</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username*" value="{{ old('username') }}" required>
                    @error('username')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email*" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="password">Password *</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password*" required>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="password_confirmation">Confirm Password *</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm Password*" required>
                        @error('password_confirmation')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <p>By registering your details, you agree with our Terms & Conditions, and Privacy and Cookie Policy.</p>
                </div>
                
                
                
                <div class="form-group">
                    <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium">Create An Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
