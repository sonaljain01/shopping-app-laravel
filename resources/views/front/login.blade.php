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
                        <li class="breadcrumb-item active" aria-current="page">Login</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
    <div class="container">
        <div class="row align-items-start justify-content-between">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 fluid">

                <form method="POST" action="{{ route('front.login') }}" class="border p-3 rounded">
                    @csrf

                    <div class="form-group">
                        <label for="email">{{ __('email') }} *</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('email') }}*"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="password">{{ __('password') }} *</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="{{ __('password') }}*" required>
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
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit"
                                class="btn btn-md full-width bg-dark text-light fs-md ft-medium">{{ __('login') }}</button>


                            <a class="btn btn-link" href="#">
                                {{ __('Forgot Your Password') }}?
                            </a>
                        </div>
                    </div>
                    <div class="form-group">
                        <p>{{ __('Not a member') }}? <a href="{{ route('front.register') }}">{{ __('register') }}</a></p>
                    </div>

                    {{-- login with google --}}
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <a href="{{ route('login.google') }}" class="btn btn-danger">
                                {{ __('Login with Google') }}
                            </a>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <a href="{{ route('login.facebook') }}" class="btn btn-danger">
                                {{ __('Login with Facebook') }}
                            </a>
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>

@endsection
