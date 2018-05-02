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
                         @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="signup-form" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <!-- <label for="email" class="col-md-4 control-label">E-Mail Address</label> -->
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="E-Mail Address">
                             @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif                            
                        </div>
                            <div class="form-group text-center margin-20t">
                                <button type="submit" class="btn btn-primary login-btn">
                                   Send Password Reset Link
                                </button>
                            </div>  
                            <div class="text-center margin-10t">
                                <span>Already Have An Account ? </span><a href="{{url('/')}}">Login Here.</a>
                            </div>                  
                        </form>                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-5">
                                <button type="submit" class="btn btn-primary">
                                    Send Password Reset Link
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endsection
<style type="text/css" media="screen">
.navbar{
    display: none;
}    
</style>