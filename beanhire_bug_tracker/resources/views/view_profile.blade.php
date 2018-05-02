<!DOCTYPE html>
<html ng-app="BugTracker">
    <head>
        <meta charset="utf-8">
        <title>SanTrack</title>
        <link rel="icon" href="{{ asset('public/images/icon.jpg') }}" sizes="16x16 32x32" type="image/png">
        <link rel="stylesheet" href="{{ URL::asset('public/css/bugTracker.css') }}">
        <script type="text/javascript" src="{{URL::asset('public/js/jquery-3.2.1.min.js')}}"></script>
        <link rel="stylesheet" href="{{ URL::asset('public/css/materialdesignicons.min.css') }}">
        <script type="text/javascript" src="{{URL::asset('public/js/bower_components/tinymce/tinymce.js')}}"></script>
        <script type="text/javascript" src="{{URL::asset('public/js/bower_components/angular/angular.js')}}"></script>
        <script type="text/javascript" src="{{URL::asset('public/js/bower_components/angular-ui-tinymce/src/tinymce.js')}}"></script>

        <script type="text/javascript" src="{{URL::asset('public/js/bootstrap.min.js')}}"></script>
        <link type="text/css" rel="stylesheet" href="{{URL::asset('public/css/bootstrap.min.css')}}">
        <script type="text/javascript" src="{{URL::asset('public/js/ui-bootstrap-tpls-2.5.0.min.js')}}" charset="utf-8"></script>
        <script src="{{URL::asset('public/js/bugTracker.js')}}" charset="utf-8"></script>
        <script type="text/javascript">
            var root_url = "{{ url('/') }}"+"/";
            var Profile = <?php echo json_encode($data); ?>;
        </script>
        <script src="{{URL::asset('public/js/bower_components/angular-material/angular-material.min.js')}}" charset="utf-8"></script>
        <link rel="stylesheet" href="{{URL::asset('public/js/bower_components/angular-material/angular-material.min.css')}}">
        <script src="{{URL::asset('public/js/bower_components/angular-animate/angular-animate.min.js')}}" charset="utf-8"></script>
        <script src="{{URL::asset('public/js/bower_components/angular-aria/angular-aria.min.js')}}" charset="utf-8"></script>
        <script src="{{URL::asset('public/js/bower_components/angular-messages/angular-messages.min.js')}}" charset="utf-8"></script>
        <script src="{{URL::asset('public/js/app-core.js')}}" charset="utf-8"></script>
        <link rel="stylesheet" href="{{ URL::asset('public/css/style.css') }}">
        <script src="{{URL::asset('public/js/bugTracker.js')}}" charset="utf-8"></script>
        <script src="{{asset('public/js/paginate.js')}}" charset="utf-8"></script>
        <script src="{{asset('public/js/angular-confirm.min.js')}}" charset="utf-8"></script>
        <link rel="stylesheet" href="{{ URL::asset('public/css/angular-confirm.min.css') }}">
        <script src="{{asset('public/js/angucomplete-alt.js')}}" charset="utf-8"></script>
        <link rel="stylesheet" href="{{ URL::asset('public/css/angucomplete-alt.css') }}">
    <script type="text/javascript" src="{{URL::asset('public/js/angularjs-dropdown-multiselect.js')}}"></script>
        
        <style media="screen">
            .error.form-control {
              border:1px solid red;
            }
        </style>
    </head>
    <body ng-controller="ProfileController" class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden" ng-cloak>
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
        <!-- <ul class="nav navbar-nav d-md-down-none">
          <li class="nav-item px-3">
            <a class="nav-link" href="#">Dashboard</a>
          </li>
          <li class="nav-item px-3">
            <a class="nav-link" href="#">Users</a>
          </li>
          <li class="nav-item px-3">
            <a class="nav-link" href="#">Settings</a>
          </li>
        </ul> -->
        <ul class="nav navbar-nav ml-auto">
          <!-- <li class="nav-item d-md-down-none">
            <a class="nav-link" href="#"><i class="icon-bell"></i><span class="badge badge-pill badge-danger">5</span></a>
          </li>
          <li class="nav-item d-md-down-none">
            <a class="nav-link" href="#"><i class="icon-list"></i></a>
          </li>
          <li class="nav-item d-md-down-none">
            <a class="nav-link" href="#"><i class="icon-location-pin"></i></a>
          </li> -->
          <!-- <li class="nav-item">
            <a class="btn btn-danger btn-outline" href="{{ route('logout') }}"
            onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
             <i class="fa fa-lock"></i> Logout
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
          </li>
           <li><a href="{{url('change_password')}}">Change Password</a></li>
           <li><a href="{{url('profile')}}/{{auth()->user()->id}}">Profile</a></li> -->
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
    <!-- <button class="navbar-toggler aside-menu-toggler" type="button">
      <span class="navbar-toggler-icon">
          <i class="mdi mdi-menu"></i>
      </span>
    </button> -->
    <!-- <a class="dropdown-item" href="{{ route('logout') }}"
        onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
       <i class="fa fa-lock"></i> Logout
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form> -->
    </header>
    <div class="app-body">
        <div class="sidebar">
            <nav class="sidebar-nav">
                <ul class="nav">
                    <!-- <li class="nav-item active">
                        <a class="nav-link" href="#!">Home</a>
                    </li>  -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('/viewBugs')}}">Bugs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('/projects')}}">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a ng-if="{{auth()->user()->is_admin}} == 1" class="nav-link" href="{{url('/roles_permissions')}}">Roles & Permissions</a>
                    </li>
                    <li class="nav-item ">
                        <a ng-if="{{auth()->user()->is_admin}} == 1" class="nav-link" href="{{url('/view_users')}}">Users</a>
                    </li>

                </ul>
            </nav>
            <button class="sidebar-minimizer brand-minimizer" type="button"></button>
        </div>
        <main class="main">
          <!-- Breadcrumb -->
           <form ng-submit="saveProfile(user)" name="profileForm" novalidate>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">SanTrack</a></li>
                  <li class="breadcrumb-item active">Profile</li>

                  <!-- Breadcrumb Menu-->
                  <li class="breadcrumb-menu d-md-down-none margin-5t">
                   <!-- <div class="btn-group" role="group" aria-label="Button group">
                      <a class="btn" href="#"><i class="icon-speech"></i></a>
                      <a class="btn" href="./"><i class="icon-graph"></i> &nbsp;Dashboard</a>
                      <a class=btn"" href="#"><i class="icon-settings"></i> &nbsp;Settings</a>
                    </div>-->
                     <!--  <input type="button" class="btn btn-primary btn-outline"  value="Add Project" ng-click="addProject()">
                      <input type="button" class="btn btn-success btn-outline"  value="Add Sprint" ng-click="addSprint()"> -->
                       <input type="submit" class="btn btn-primary btn-outline"  value="Submit">
                  </li>
              </ol>
              <div class="container-fluid list-view">
                  <div class="animated fadeIn">
                    <div class="col-md-10 col-md-offset-1">

                        <div class="col-md-6">
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-4"><label for="fname" class="form-label">First Name</label></div>
                                  <div class="col-md-8">
                                    <input id="fname" type="text"  ng-model="user.first_name" name="first_name" class="form-control" ng-class="{true: 'error'}[submitted && profileForm.first_name.$invalid]" required>
                                  </div>
                              </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-4"> <label for="lName" class="form-label">Last Name</label></div>
                                  <div class="col-md-8"> <input id="lName" type="text"  ng-model="user.last_name" name="last_name" class="form-control" ng-class="{true: 'error'}[submitted && profileForm.last_name.$invalid]" required></div>
                              </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-4"><label for="email" class="form-label">Email</label></div>
                                  <div class="col-md-8"><input id="email" type="email"  ng-model="user.email" name="email" class="form-control" ng-class="{true: 'error'}[submitted && profileForm.email.$invalid]" required></div>
                              </div>
                          </div>
                        </div>
                    </div>
                  </div>
              </div>
          </main>
        </form>
    </div>

    </body>
</html>
