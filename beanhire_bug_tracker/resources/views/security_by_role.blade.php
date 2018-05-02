<!DOCTYPE html>
<html ng-app = "BugTracker">
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
        <link rel="stylesheet" href="{{ URL::asset('public/css/style.css') }}">
        <script src="{{URL::asset('public/js/bugTracker.js')}}" charset="utf-8"></script>
        <script src="{{asset('public/js/paginate.js')}}" charset="utf-8"></script>
        <!-- <script src="{{asset('public/js/ng-confirm.js')}}" charset="utf-8"></script> -->
        <script src="{{asset('public/js/angular-confirm.min.js')}}" charset="utf-8"></script>
        <script type="text/javascript" src="{{URL::asset('public/js/angularjs-dropdown-multiselect.js')}}"></script>

        <script src="{{asset('public/js/angucomplete-alt.js')}}" charset="utf-8"></script>
        <link rel="stylesheet" href="{{ URL::asset('public/css/angucomplete-alt.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('public/css/angular-confirm.min.css') }}">
        <script type="text/javascript">
            var root_url = "{{ url('/') }}"+"/";
            var Roles = <?php echo json_encode($roles_list); ?>;
            var Users = <?php echo json_encode($users); ?>;
            var Modules = <?php echo json_encode($modules); ?>;
            var permissions = <?php echo json_encode($permissions_list_arr);?>;
        </script>
        <style type="text/css">
            .error.form-control {
              border:1px solid red;
            }
        </style>
    </head>
    <body ng-controller="permisssionsController" class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden" ng-cloak>
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
                        <li class="nav-item active">
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
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">SanTrack</a></li>
                    <li class="breadcrumb-item active">Roles & Permissions</li>

                    <!-- Breadcrumb Menu-->
                    <li class="breadcrumb-menu d-md-down-none margin-5t">
                     <!-- <div class="btn-group" role="group" aria-label="Button group">
                        <a class="btn" href="#"><i class="icon-speech"></i></a>
                        <a class="btn" href="./"><i class="icon-graph"></i> &nbsp;Dashboard</a>
                        <a class=btn"" href="#"><i class="icon-settings"></i> &nbsp;Settings</a>
                      </div>-->
                        <!-- <select  ng-model='default_project' convert-to-number  class="breadcrumb-item default-project" ng-change="setDefaultProject()">
                            <option ng-repeat='activeProject in activeProjects ' value="@{{activeProject.id}}" >@{{activeProject.name}}</option>
                        </select>
                        <input type="button" class="btn btn-primary btn-outline"  value="AddProject" ng-click="addProject()">
                        <input type="button" class="btn btn-success btn-outline"  value="AddSprint" ng-click="addSprint()"> -->
                         <a href="#" class=" btn btn-success btn-outline" ng-click="showUserForm()">Add Role</a>
                    </li>
                </ol>

                <div class="container-fluid">
                    <div class="animated fadeIn">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <th>Role</th>
                                    <th>Created By</th>
                                    <th>Last Updated By</th>
                                    <th>Comments</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </thead>
                                <tbody ng-init="highlight_row = 1">
                                    <tr ng-repeat="role in roles" ng-click="highlightRow($index)" ng-class="{true: 'selected'}[highlightRow == $index]">
                                        <td><a style="display:block;" ng-click="setRoleID(role.id)">@{{role.name}}</a></td>
                                        <td>@{{users[role.created_by]}}</td>
                                        <td>@{{users[role.updated_by]}}</td>
                                        <td>@{{role.comments}}</td>
                                        <td>@{{role.created_at|dateFilter}}</td>
                                        <td><a ng-click = "deleteRole(role,roles)"><i class="mdi mdi-delete"></i></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                            <!-- <input type="button" name="" value="Add User" ng-click="showUserForm()"> -->
                        <div class="panel panel-default"  ng-show='userForm'>
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-md-5">
                                        <span class="primary-color">Add Role</a>
                                    </div>
                                    <div class="col-md-7 text-right">
                                        <a  class="primary-color" ng-click="addRole(role)">Save</a>&nbsp;
                                    <a class="red-color" ng-click="showUserForm()">Cancel</a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <form name="roleForm" novalidate>
                                    <div  class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 margin-5b">
                                                <span for="name">Role Name</span>
                                                <input id="name" class="form-control" ng-model="role.name" ng-class="{true: 'error'}[submitted && roleForm.name.$invalid]" type="text" name="name"  required>
                                            </div>
                                            <div class="col-md-6 margin-5b">
                                                <span>Access Similar To</span>
                                                <select class="form-control " name="" ng-model="role.similarTo">
                                                    <option value="">Select</option>
                                                    <option ng-repeat="role in roles" value="@{{role.id}}">@{{role.name}}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <span>Comments:</span>
                                                <textarea name="comments" ng-class="{true: 'error'}[submitted && roleForm.comments.$invalid]" class="form-control" ng-model="role.comments" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @include('modules_list')
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
