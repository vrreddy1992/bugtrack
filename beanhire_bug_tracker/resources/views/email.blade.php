<!DOCTYPE html>
<html ng-app="BugTracker">
    <head>
        <meta charset="utf-8">
        <title></title>
        <style>
        .error.form-control {
          border:1px solid red;
        }
        </style>
        <link rel="icon" href="{{ asset('public/images/icon.jpg') }}" sizes="16x16 32x32" type="image/png">
        <link rel="stylesheet" href="{{ URL::asset('public/css/bugTracker.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('public/css/materialdesignicons.min.css') }}">
        <script type="text/javascript" src="{{URL::asset('public/js/jquery-3.2.1.min.js')}}"></script>
        <script type="text/javascript" src="{{URL::asset('public/js/bower_components/tinymce/tinymce.js')}}"></script>
        <script type="text/javascript" src="{{URL::asset('public/js/bower_components/angular/angular.js')}}"></script>
        <script type="text/javascript" src="{{URL::asset('public/js/bower_components/angular-ui-tinymce/src/tinymce.js')}}"></script>
        <script type="text/javascript" src="{{URL::asset('public/js/bootstrap.min.js')}}"></script>
            <link type="text/css" rel="stylesheet" href="{{URL::asset('public/css/bootstrap.min.css')}}">
        <script type="text/javascript" src="{{URL::asset('public/js/ui-bootstrap-tpls-2.5.0.min.js')}}" charset="utf-8"></script>
        <script src="{{URL::asset('public/js/bugTracker.js')}}" charset="utf-8"></script>
            <script src="{{URL::asset('public/js/bower_components/angular-material/angular-material.min.js')}}" charset="utf-8"></script>
            <link rel="stylesheet" href="{{URL::asset('public/js/bower_components/angular-material/angular-material.min.css')}}">
            <script src="{{URL::asset('public/js/bower_components/angular-animate/angular-animate.min.js')}}" charset="utf-8"></script>
            <script src="{{URL::asset('public/js/bower_components/angular-aria/angular-aria.min.js')}}" charset="utf-8"></script>
            <script src="{{URL::asset('public/js/bower_components/angular-messages/angular-messages.min.js')}}" charset="utf-8"></script>
            <script src="{{URL::asset('public/js/app-core.js')}}" charset="utf-8"></script>
            <script src="{{asset('public/js/paginate.js')}}" charset="utf-8"></script>
            <link rel="stylesheet" href="{{ URL::asset('public/css/style.css') }}">
            <script src="{{asset('public/js/angular-confirm.min.js')}}" charset="utf-8"></script>
            <link rel="stylesheet" href="{{ URL::asset('public/css/angular-confirm.min.css') }}">
            <script type="text/javascript">
            </script>
            <script type="text/javascript" src="{{URL::asset('public/js/angularjs-dropdown-multiselect.js')}}"></script>

        <script type="text/javascript">
            var ID = 0;
            var root_url = "{{ url('/') }}"+"/";
        </script>
        <script src="{{asset('public/js/angucomplete-alt.js')}}" charset="utf-8"></script>
        <link rel="stylesheet" href="{{ URL::asset('public/css/angucomplete-alt.css') }}">
    </head>
    <body ng-controller="PasswordController">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="comp-logo margin-30t text-center">
                        <img src="{{ URL::asset('public/images/logo.png') }}" />
                    </div>
                    <form  name="resetForm" novalidate  class="margin-20t">
                        <div class="form-group">
                            <label for="password">Email</label>
                            <input  class="form-control" ng-class="{true: 'error'}[submitted && resetForm.email.$invalid]" type="email" ng-model="user.email" name="email" required>
                        </div>
                        <div class="from-group text-center">
                            <input type="button" class="btn btn-primary btn-outline" ng-click="sendResetMail(user)" value="Send Mail">
                        </div>
                        <div class="text-center margin-10t">
                                <span></span><a href="{{url('/')}}">Login Here.</a>
                        </div> 
                    </form>
                </div>
            </div>
        </div>
        
    </body>
</html>
