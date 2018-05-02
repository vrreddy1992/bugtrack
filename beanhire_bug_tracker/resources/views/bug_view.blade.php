<!DOCTYPE html>
<html ng-app="BugTracker">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SanTrack</title>
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
   <!--  <script src="{{asset('public/js/ng-confirm.js')}}" charset="utf-8"></script> -->
    <link rel="stylesheet" href="{{ URL::asset('public/css/style.css') }}">
    <script src="{{asset('public/js/angular-confirm.min.js')}}" charset="utf-8"></script>
    <link rel="stylesheet" href="{{ URL::asset('public/css/angular-confirm.min.css') }}">
    <script src="{{asset('public/js/angucomplete-alt.js')}}" charset="utf-8"></script>
    <link rel="stylesheet" href="{{ URL::asset('public/css/angucomplete-alt.css') }}">
    <script type="text/javascript" src="{{URL::asset('public/js/angularjs-dropdown-multiselect.js')}}"></script>
    


<script type="text/javascript">
    var root_url = "{{ url('/') }}"+"/";
    var projects = <?php echo json_encode($projects); ?>;
    // var modules = <?php echo json_encode($modules); ?>;
    var sprints = <?php echo json_encode($sprints); ?>;
    var bugstatus = <?php echo json_encode($status); ?>;
    var severity = <?php echo json_encode($severity); ?>;
    var users = <?php echo json_encode($users); ?>;
    var bugCode = <?php echo $currentBugId; ?>;
    var bug = <?php echo json_encode($bugData);?>;
    var operationType = <?php echo $operation; ?>;
    var DefaultProject = <?php echo $default_project; ?>;
    var DefaultAssignedTo = <?php echo $default_reportedby ; ?>;
    var Files = <?php echo json_encode($files); ?>;
    var allActiveProjects = <?php echo json_encode($allActiveProjects); ?>;
</script>
<style>
img{
    height: 200px;
    width: 50%;
}
.error.form-control {
  border:1px solid red;
}
</style>
</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden" ng-cloak>
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
        <li class="form-group margin-15t margin-10r">
            <select  ng-model='default_project' convert-to-number  class="breadcrumb-item form-control" ng-change="setDefaultProject()">
              <option value="">Select</option>
              <option ng-repeat='activeProject in activeProjects' value="@{{activeProject.id}}" >@{{activeProject.name}}</option>
            </select>
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
    <!-- <button class="navbar-toggler aside-menu-toggler" type="button">
      <span class="navbar-toggler-icon">
          <i class="mdi mdi-menu"></i>
      </span>
    </button> -->

  </header>
  <div ng-cloak ng-show="root_error_scope" class="alert bouncy-slide-down" ng-class="{'alert-success':root_error_scope_status==1, 'alert-warning':root_error_scope_status!=1}"><span ng-bind="root_error_scope_msg"> </span><a onClick="hideAlertMessage();"><i class="mdi mdi-close"></i></a></div>
  <div class="spinner">
  <div class="cube1"></div>
  <div class="cube2"></div>
</div>
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
        <div ng-controller="addBug">
            <form method="post" name="bugForm" ng-submit = "saveBug(bug)" novalidate>
      <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">SanTrack</a></li>
                    <li ng-if="operationType == 'view'">View Bug</li>
                    <li ng-if="operationType == 'edit'">Edit Bug</li>
                    <li ng-if="operationType == 'add'">Add Bug</li>

            <!-- Breadcrumb Menu-->
                    <li class="breadcrumb-menu d-md-down-none margin-5t">
             <!-- <div class="btn-group" role="group" aria-label="Button group">
                <a class="btn" href="#"><i class="icon-speech"></i></a>
                <a class="btn" href="./"><i class="icon-graph"></i> &nbsp;Dashboard</a>
                <a class=btn"" href="#"><i class="icon-settings"></i> &nbsp;Settings</a>
              </div>-->
                      <input ng-if="operationType == 'add'" type="submit" class="btn btn-primary btn-outline" name="submit" value="Save">
                      <input ng-if="operationType == 'edit'" type="submit" class="btn btn-primary btn-outline" name="submit" value="Save">
                      <a  ng-if="operationType == 'view'" class="btn btn-warning btn-outline" href="{{url('/editBug')}}/@{{bug.id}}">Edit</a>
                      <a class="btn btn-danger btn-outline" href="{{url('/viewBugs')}}">Cancel</a>
                    </li>
                </ol>
                <div class="container-fluid">
                    <div class="animated fadeIn">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                  <div class="col-md-1 padding-0r">
                                      <span class="bug-id">Bug Id- @{{bug.bug_code}}</span>
                                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                      <input type="hidden"  ng-model="bug.bug_code"  disabled class="form-control">
                                  </div>
                                                        <!-- <div class="col-md-1">
                                                            <label ng-if="operationType != 'view'">Bug Title:</label>
                                                        </div> -->
                                </div>
                                <div class="form-group">
                                  <div class="row">
                                    <div class="col-md-3" class="form-group">
                                        <span for="title" class="view-title">Bug Title:</span>
                                        <input id="title" type="text" ng-class="{true: 'error'}[submitted && bugForm.title.$invalid]" name="title" ng-model="bug.title"  ng-if="operationType != 'view'" class="form-control" placeholder="Bug Title" required>
                                        <h4 ng-if="operationType == 'view'">@{{bug.title}}</h4>
                                    </div>
                                    <div class="col-md-3">
                                        <span for="project" class="view-title">Project:</span>
                                      <select id="project" ng-model='bug.project_id' name="project" ng-class="{true: 'error'}[submitted && bugForm.project.$invalid]" convert-to-number class="form-control" ng-disabled="operationType == 'view'" ng-change="viewSprints()" required>
                                        <!-- <option value="">Select</option> -->
                                        <option ng-repeat='(key,value) in projects' value="@{{key}}" >@{{value}}</option>
                                      </select>
                                    </div>
                                    <div class="col-md-3">
                                        <span for="sprint" class="view-title">Sprint</span>
                                      <select id="Sprint" ng-model='bug.sprint_id' name="sprint" ng-class="{true: 'error'}[submitted && bugForm.sprint.$invalid]" convert-to-number class="form-control" ng-disabled="operationType == 'view'" required>
                                        <!-- <option value=""></option> -->
                                        <option ng-repeat='(key,value) in sprints ' value="@{{key}}" >@{{value}}</option>
                                      </select>
                                    </div>
                                    <!-- <div class="col-md-3">
                                        <span for="module" class="view-title">Module</span>
                                      <select id="module" ng-model='bug.module_id' name="module" ng-class="{true: 'error'}[submitted && bugForm.module.$invalid]" convert-to-number class="form-control" ng-disabled="operationType == 'view'" required>

                                        <option ng-repeat='(key,value) in modules ' value="@{{key}}" >@{{value}}</option>
                                      </select>
                                    </div> -->
                                    <div class="col-md-3">
                                        <span for="severity" class="view-title">Severity</span>
                                        <select id="severity" ng-model='bug.severity_id' name="severity" ng-class="{true: 'error'}[submitted && bugForm.severity.$invalid]" convert-to-number class="form-control" ng-disabled="operationType == 'view'" required>
                                          <!-- <option value="">Severity</option> -->
                                          <option ng-repeat='(key,value) in severities' value="@{{key}}" >@{{value}}</option>
                                        </select>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <span for="status" class="view-title">Status</span>
                                            <select id="status" name="status" ng-model='bug.status_id' ng-class="{true: 'error'}[submitted && bugForm.status.$invalid]" convert-to-number class="form-control" ng-disabled="operationType == 'view'" required>
                                                <!-- <option value="">Status</option> -->
                                                <option ng-repeat='(key,value) in bugstatus'   value="@{{key}}" >@{{value}}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <span for="reported_by" class="view-title">Reported By</span>
                                            <select id="reported_by" ng-model='bug.reported_by' name='reported_by' ng-class="{true: 'error'}[submitted && bugForm.reported_by.$invalid]" convert-to-number class="form-control" ng-disabled="operationType == 'view'" required>
                                                <!-- <option value="">Reported By</option> -->
                                                <option ng-repeat='(key,value) in users'    value="@{{key}}" >@{{value}}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <span for="assigned_to" class="view-title">Assigned To</span>
                                            <select id="assigned_to" ng-model='bug.assigned_to' name='assigned_to' ng-class="{true: 'error'}[submitted && bugForm.assigned_to.$invalid]" convert-to-number class="form-control" ng-disabled="operationType == 'view'" required>
                                                <!-- <option value="">Assigned To</option> -->
                                                <option ng-repeat='(key,value) in users' value="@{{key}}" >@{{value}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group margin-10t bug-description">
                                    <div ng-if="operationType != 'view'">
                                        <textarea ui-tinymce="tinymceOptions" ng-model="bug.description" class="form-control" style="height:300px;" required></textarea>
                                    </div>
                                    <div ng-if="operationType == 'view'">
                                        <?php
                                        if($operation == "'view'"){
                                        echo $bugData['description'];
                                        }
                                    ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="attatchment">
                                                <span class="sub-text">Attachments</span>
                                                <label class="file-upload">
                                                <input type='file' ng-model="bug.attachments" onchange="angular.element(this).scope().add_file(this)" ng-if="operationType != 'view'">
                                                            Upload File
                                                </label>
                                                <ul class="upload-list">
                                                    <!-- href="{{url('profile')}}/{{auth()->user()->id}}" -->
                                                    <li ng-repeat="file in attachments" ><a href="{{url('/downloadFile')}}/@{{file.id}}">@{{file.name}}</a>
                                                         <a ng-if="operationType != 'view'" ng-click = "deleteFile(file,attachments)"><i class="mdi mdi-delete"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <!-- Main content -->

</div>
</body>
</html>
