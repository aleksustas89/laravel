@extends('auth.main')

@section('content')

    <form method="POST" class="my-4" action="{{ route('login') }}">
        @csrf            
        <div class="form-group mb-2">
            <label class="form-label" for="username">Username</label>
            <input type="text" required class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="{{ __('Email Address') }}" autofocus>                               
        </div><!--end form-group--> 

        <div class="form-group">
            <label class="form-label" for="userpassword">Password</label>                                            
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}">                            
        </div><!--end form-group--> 

        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <div class="form-group row mt-3">
            <div class="col-sm-6">
                <div class="form-check form-switch form-switch-success">
                    <input  class="form-check-input" type="checkbox" id="remember" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
                </div>
            </div><!--end col--> 
        </div><!--end form-group--> 

        <div class="form-group mb-0 row">
            <div class="col-12">
                <div class="d-grid mt-3">
                    <input type="hidden" name="auth_type" value="1" />
                    <button class="btn btn-primary" type="submit">{{ __('Login') }} <i class="fas fa-sign-in-alt ms-1"></i></button>
                </div>
            </div><!--end col--> 
        </div> <!--end form-group-->                           
    </form><!--end form-->


@endsection
