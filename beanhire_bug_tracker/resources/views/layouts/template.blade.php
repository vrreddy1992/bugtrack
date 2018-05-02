<!DOCTYPE html>
<html ng-app = "TestCases">
<head>
  <title>SanTrack</title>
  <link rel="icon" href="{{ asset('public/images/icon.jpg') }}" sizes="16x16 32x32" type="image/png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="{{ URL::asset('public/css/bugTracker.css') }}">
    <script type="text/javascript" src="{{URL::asset('public/js/jquery-3.2.1.min.js')}}"></script>
    <link rel="stylesheet" href="{{ URL::asset('public/css/materialdesignicons.min.css') }}">
    <script type="text/javascript" src="{{URL::asset('public/js/bower_components/tinymce/tinymce.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('public/js/bower_components/angular/angular.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('public/js/bower_components/angular-ui-tinymce/src/tinymce.js')}}"></script>
    <script src="{{URL::asset('public/js/testCases.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{URL::asset('public/js/jquery-3.2.1.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('public/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('public/js/alasql.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('public/js/xlsx.core.min.js')}}"></script>
    <link type="text/css" rel="stylesheet" href="{{URL::asset('public/css/bootstrap.min.css')}}">
    <script type="text/javascript" src="{{URL::asset('public/js/ui-bootstrap-tpls-2.5.0.min.js')}}" charset="utf-8"></script>
    <script src="{{URL::asset('public/js/bower_components/angular-material/angular-material.min.js')}}" charset="utf-8"></script>
    <link rel="stylesheet" href="{{URL::asset('public/js/bower_components/angular-material/angular-material.min.css')}}">
    <script src="{{URL::asset('public/js/bower_components/angular-animate/angular-animate.min.js')}}" charset="utf-8"></script>
    <script src="{{URL::asset('public/js/bower_components/angular-aria/angular-aria.min.js')}}" charset="utf-8"></script>
    <script src="{{URL::asset('public/js/bower_components/angular-messages/angular-messages.min.js')}}" charset="utf-8"></script>
    <script src="{{URL::asset('public/js/app-core.js')}}" charset="utf-8"></script>
    <script src="{{asset('public/js/paginate.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{URL::asset('public/js/angularjs-dropdown-multiselect.js')}}"></script>
    
    <!-- <script src="{{asset('public/js/ng-confirm.js')}}" charset="utf-8"></script> -->
    <link rel="stylesheet" href="{{ URL::asset('public/css/style.css') }}">
    <!-- <script src="{{URL::asset('public/js/app.js') }}"></script> -->

    <script src="{{asset('public/js/angular-confirm.min.js')}}" charset="utf-8"></script>
    <link rel="stylesheet" href="{{ URL::asset('public/css/angular-confirm.min.css') }}">
    <script src="{{asset('public/js/angucomplete-alt.js')}}" charset="utf-8"></script>
    <link rel="stylesheet" href="{{ URL::asset('public/css/angucomplete-alt.css') }}">
    <script type="text/javascript">
        var root_url = "{{ url('/') }}"+"/";
    </script>
</head>
<body   class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden" ng-cloak>
    <header class="app-header navbar">
        <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
          <span class="navbar-toggler-icon">
              <i class="mdi mdi-menu"></i>
          </span>
        </button>
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
          <span class="navbar-toggler-icon">
              <i class="mdi mdi-menu"></i>
          </span>
        </button>

        <ul class="nav navbar-nav ml-auto">
          <li  class="breadcrumb-menu d-md-down-none margin-15t margin-10r">
            <a ng-if="permissions.edit_access" class="btn btn-primary btn-outline" href="{{url('/addBug')}}">Add Bug</a>
          </li>
           <li class="form-group margin-15t margin-10r">
         </li>
            <li class="form-group margin-15t margin-10r">
        </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle nav-link margin-15t" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
              <span class="d-md-down-none">{{auth()->user()->first_name}} {{auth()->user()->last_name}}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="dropdown-header text-center">
                <strong>Account</strong>
              </div>
              <a class="dropdown-item" href="{{url('profile')}}/{{auth()->user()->id}}">Profile</a>
              <a class="dropdown-item" href="{{url('change_password')}}">Change Password</a>
              <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
              <i class="fa fa-lock"></i> Logout
            </a>

              <form id="logout-form"  action="{{ route('logout') }}" method="POST" style="display: none;" >
                  {{ csrf_field() }}
              </form>
            </div>
          </li>
        </ul>
  </header>
  <div ng-cloak ng-show="root_error_scope" class="alert bouncy-slide-down" ng-class="{'alert-success':root_error_scope_status==1, 'alert-warning':root_error_scope_status!=1}"><span ng-bind="root_error_scope_msg"> </span><a onClick="hideAlertMessage();"><i class="mdi mdi-close"></i></a></div>
  <div class="app-body">
    <div class="sidebar">
        <nav class="sidebar-nav">
            <ul class="nav">
                <li class="nav-item active">
                    <a class="nav-link" href="{{url('/viewBugs')}}">Bugs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/projects')}}">Projects</a>
                </li>
                <li class="nav-item ">
                    <a ng-if="{{auth()->user()->is_admin}} == 1" class="nav-link" href="{{url('/roles_permissions')}}">Roles & Permissions</a>
                </li>
                <li class="nav-item ">
                    <a ng-if="{{auth()->user()->is_admin}} == 1" class="nav-link" href="{{url('/view_users')}}">Users</a>
                </li>
                <li class="nav-item ">
                    <a  class="nav-link" href="{{url('/modules')}}">Testing</a>
                </li>
            </ul>
        </nav>
      <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>
    <main class="main" ng-cloak>
        @yield('content')
    </main>
</div> 
</div>
</body>
</html>
