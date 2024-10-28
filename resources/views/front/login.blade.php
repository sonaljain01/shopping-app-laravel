@extends('front.layouts.app')

@section('content')

    <div class="container">
        <div class="row align-items-start justify-content-between">
        
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <form action="{{ route('front.login') }}" method="POST" class="border p-3 rounded">	
                    @csrf			
                    <div class="form-group">
                        <label>User Name *</label>
                        <input type="text" name="username" class="form-control" placeholder="Username*">
                    </div>
                    
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" name="password" class="form-control" placeholder="Password*">
                    </div>
                    
                    <div class="form-group">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-1">
                                <input id="dd" class="checkbox-custom" name="remember" type="checkbox">
                                <label for="dd" class="checkbox-custom-label">Remember Me</label>
                            </div>	
                            <div class="eltio_k2">
                                <a href="#">Lost Your Password?</a>
                            </div>	
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium">Login</button>
                    </div>
                </form>
            </div>
            
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mfliud">
                <form action="{{ route('front.register') }}" method="POST" class="border p-3 rounded">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>First Name *</label>
                            <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control" placeholder="Last Name">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" class="form-control" placeholder="Email*" required>
                    </div>
                    
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Password *</label>
                            <input type="password" name="password" class="form-control" placeholder="Password*" required>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label>Confirm Password *</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password*" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <p>By registering your details, you agree with our Terms & Conditions, and Privacy and Cookie Policy.</p>
                    </div>
                    
                    <div class="form-group">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-1">
                                <input id="ddd" class="checkbox-custom" name="newsletter" type="checkbox">
                                <label for="ddd" class="checkbox-custom-label">Sign me up for the Newsletter!</label>
                            </div>		
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium">Create An Account</button>
                    </div>
                </form>
            </div>
            
            
        </div>
    </div>

@endsection