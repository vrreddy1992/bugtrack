@extends('layouts.app')

@section('content')

<div class="main-body">
    <div class="signup-container">
        <div class="container">
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
                        <h3 class="signup-title">Login</h3>
                        <form class="signup-form" method="POST" action="{{ route('login') }}" novalidate>
                        {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                               <!--  <label for="email" class="col-md-4 control-label">E-Mail Address</label> -->
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="E-Mail Address" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} margin-5b">
                            <!-- <label for="password" class="col-md-4 control-label">Password</label> -->
                                <input id="password" type="password" class="form-control" name="password" required placeholder="Password">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div> 
                            <div class="form-group">
                            <label style="color: #666;">
                               <input type="checkbox"  name="remember" {{ old('remember') ? 'checked' : '' }}> Remember me
                           </label>
                                <a class="btn btn-link pull-right" href="{{ route('password.request') }}"> Forgot Your Password? </a>
                            </div> 
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary login-btn">
                                    Login
                                </button>
                            </div>                    
                        </form>                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} margin-5b">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox pull-left">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                                <a class="btn btn-link pull-right" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-7 col-md-offset-5">
                                <button type="submit" class="btn btn-primary login-btn">
                                    Login
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
-->
@endsection
<style type="text/css" media="screen">
.navbar{
    display: none;
}    
</style>