@extends('layouts.app')

@section('content')

<div class="main-body">
    <div class="signup-container">
        <div class="container-fluid">
            <div class="row">
                <!-- <div class="col-md-5 padding-0">
                    <div class="background-image">
                        <div class="background-content">
                            <h2>Hello User</h2>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
                        </div>
                    </div>
                </div> -->
                <div class="col-md-6 col-md-offset-3">
                    <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="signup-logo text-center">
                                    <img src="{{ asset('public/images/logo.png') }}" alt="Beanhire" style="max-width:100%">
                                </div>
                            </div>
                        </div>
                    <div class="signup">
                        <h3 class="signup-title">Reset Password</h3>
                        <form class="signup-form" method="POST" action="{{ route('password.request') }}" novalidate>
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">
                        <!-- <label for="email" class="col-md-4 control-label">E-Mail Address</label> -->
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}" style="display: none">
                            <input id="email" type="hidden" class="form-control" name="email" value="{{ $email or old('email') }}" required>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <!-- <label for="password" class="col-md-4 control-label">Password</label> -->
                            <input id="password" type="password" class="form-control" name="password" required placeholder="Password">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                           <!--  <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label> -->
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm Password">
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                            <div class="form-group text-center margin-20t">
                                <button type="submit" class="btn btn-primary login-btn">
                                   Reset Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<style type="text/css" media="screen">
.navbar{
    display: none;
}
</style>
