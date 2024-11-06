@extends('front.layouts.app')

@section('content')
    <div class="container">
        <div class="row align-items-start justify-content-between">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 fluid">

                <form method="POST" action="{{ route('front.login') }}" class="border p-3 rounded">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email*"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="password">Password *</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Password*" required>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    Remember Me
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit"
                                class="btn btn-md full-width bg-dark text-light fs-md ft-medium">Login</button>


                            <a class="btn btn-link" href="#">
                                Forgot Your Password?
                            </a>
                        </div>
                    </div>
                    <div class="form-group">
                        <p>Not a member? <a href="{{ route('front.register') }}">Register</a></p>
                    </div>

                    {{-- login with google --}}
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <a href="{{ route('login.google') }}" class="btn btn-danger">
                                Login with Google
                            </a>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <a href="{{ route('login.facebook') }}" class="btn btn-danger">
                                Login with Facebook
                            </a>
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>

@endsection
