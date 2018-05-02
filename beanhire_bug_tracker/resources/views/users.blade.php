<!DOCTYPE html>
<html ng-app = "BugTracker">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SanTrack</title>
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
    <script type="text/javascript">
        var root_url = "{{ url('/') }}"+"/";
        var Users = <?php echo json_encode($users); ?>;
        var Roles = <?php echo json_encode($roles); ?>;
        var RolesArr = <?php echo json_encode($rolesArr); ?>;
        var permissions = <?php echo json_encode($permissions_list_arr);?>;      
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
    

</head>
<body  ng-controller="usersController" class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden" ng-cloak>
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
        <ul class="nav navbar-nav d-md-down-none">

        </ul>
        <ul class="nav navbar-nav ml-auto">
            <li  class="breadcrumb-menu d-md-down-none margin-15t margin-10r">
              <a ng-if="permissions.edit_access" class="btn btn-primary btn-outline" href="{{url('/addBug')}}">Add Bug</a>
            </li>
           <li class="form-group margin-15t margin-10r">
            <div angucomplete-alt
              id="ex5"
              placeholder="Search Bugs"
              pause="500"
              selected-object="selectedBug"
              remote-url= "{{ url('/search_bugs').'?keyword='}}"
              remote-url-request-formatter="remoteUrlRequestFn"              
              title-field="title"
              description-field="description"
              minlength="2"
              input-class="form-control form-control-small"
              match-class="highlight">
            </div>
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
<!--     <div class="loader-div" ng-show="loader">
        <div class="cube1"></div>
        <div class="cube2"></div>
    </div> -->
    <div class="app-body" ng-class="{'loader-div':loader}">
        <div class="sidebar">
            <nav class="sidebar-nav">
                <ul class="nav">
                    <li class="nav-item">
                    <a class="nav-link" href="{{url('/viewBugs')}}">Bugs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/projects')}}">Projects</a>
                </li>
                <li class="nav-item ">
                    <a ng-if="{{auth()->user()->is_admin}} == 1" class="nav-link" href="{{url('/roles_permissions')}}">Roles & Permissions</a>
                </li>
                <li class="nav-item active">
                    <a ng-if="{{auth()->user()->is_admin}} == 1" class="nav-link" href="{{url('/view_users')}}">Users</a>
                </li>
                </ul>
            </nav>
            <button class="sidebar-minimizer brand-minimizer" type="button"></button>
        </div>
        <main class="main">
          <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">SanTrack</a></li>
                <li class="breadcrumb-item active">Users</li>

                <!-- Breadcrumb Menu-->
                <li class="breadcrumb-menu d-md-down-none margin-5t">
                 <!-- <div class="btn-group" role="group" aria-label="Button group">
                    <a class="btn" href="#"><i class="icon-speech"></i></a>
                    <a class="btn" href="./"><i class="icon-graph"></i> &nbsp;Dashboard</a>
                    <a class=btn"" href="#"><i class="icon-settings"></i> &nbsp;Settings</a>
                  </div>-->
                    <input type="button" class="btn btn-primary btn-outline"  value="Add User" ng-click="addUser()">
                </li>
            </ol>
            <div class="container-fluid list-view">
                <div class="animated fadeIn">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="cart">
                                <div class="card-header">
                                  <i class="fa fa-align-justify"></i> Users
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>User ID</th>
                                                <th>Role</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="user in users track by $index">
                                                     <td><a ng-click="viewUserInModal(user.id)">@{{user.first_name}}</a></td>
                                                     <td>@{{user.last_name}}</td>
                                                     <td>@{{user.email}}</td>
                                                     <td>@{{rolesArr[user.role_id]}}</td>
                                                     <td>@{{user.status|userstatus}}</td>
                                                     <td> <a ng-click = "viewUserInModal(user.id)"><i class="mdi mdi-pencil"></i></a><a ng-click = "deleteUser(user,users)"><i class="mdi mdi-delete"></i></a>
                                                     <a ng-show="user.status == 0" ng-click="sendActivationMail(user.id)"><i class="mdi  mdi-email-outline"></i></a>
                                                     </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{$usersData->links("pagination::bootstrap-4")}}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
<style>
.md-datepicker-calendar-pane{
z-index: 1200;
}
.error.form-control {
  border:1px solid red;
}
</style>
<script type="text/ng-template" id="UserModal.html">
    <div class="modal-header">
        <a class="close" data-dismiss="modal" ng-click="closeModal()">&times;</a>
        <h4  id="modal-title" class="modal-title">
            Add User
        </h4>
    </div>
    <form name="userForm" novalidate>
        <div  id="modal-body" class="modal-body">
                @{{id}}
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">First Name</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" ng-model="user.first_name"  name='first_name' ng-class="{true: 'error'}[submitted && userForm.first_name.$invalid]"placeholder="First Name" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Last Name</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" ng-model="user.last_name"  name='last_name' ng-class="{true: 'error'}[submitted && userForm.last_name.$invalid]"placeholder="Last Name" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" ng-model="user.email"  name='email' ng-class="{true: 'error'}[submitted && userForm.email.$invalid]"placeholder="Email" required>
                            <span ng-show="email_duplicated" class="red-color">Email already exists</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Role</label>
                        </div>
                        <div class="col-md-8">
                            <select ng-model='user.role_id' convert-to-number class="form-control" name='role_id' ng-class="{true: 'error'}[submitted && userForm.role_id.$invalid]" required>
                                <option value="">Select</option>
                                <option ng-repeat='role in roles ' value="@{{role.id}}" >@{{role.name}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <!-- <label class="form-label">Role</label> -->
                        </div>
                        <div class="col-md-8">
                            <input type="checkbox" ng-model="user.is_admin" id="is_admin" ng-checked="user.is_admin == 1" ng-true-value="1" ng-false-value="0"><label for="is_admin">Admin Privileges</label>
                        </div>
                    </div>
                </div>
        </div>
        <div class="modal-footer">
            <input type="button" ng-click="saveUser(user)" class="btn btn-primary btn-outline" value="Save">
            <input type="button" class="btn btn-danger btn-outline" value="Close" ng-click="closeModal()">
        </div>
    </from>
</script>
</html>
