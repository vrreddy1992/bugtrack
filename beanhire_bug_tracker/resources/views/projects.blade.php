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
        var projects = <?php echo json_encode($projects); ?>;
        var allActiveProjects = <?php echo json_encode($allActiveProjects); ?>;
        var ProjectStatuses = <?php echo json_encode($project_statuses); ?>;
        var SprintStatues = <?php echo json_encode($sprint_statuses);?>;
        var permissions_list = <?php echo json_encode($permissions_list_arr) ?>
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
    <!-- <script src="{{asset('public/js/ng-confirm.js')}}" charset="utf-8"></script> -->
    <script src="{{asset('public/js/angular-confirm.min.js')}}" charset="utf-8"></script>
    <script src="{{asset('public/js/angucomplete-alt.js')}}" charset="utf-8"></script>
    <link rel="stylesheet" href="{{ URL::asset('public/css/angucomplete-alt.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('public/css/angular-confirm.min.css') }}">
    <script type="text/javascript" src="{{URL::asset('public/js/angularjs-dropdown-multiselect.js')}}"></script>
    
</head>
<body  ng-controller="projectsController" class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden" ng-cloak>
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
          
          <li  class="breadcrumb-menu d-md-down-none margin-15t margin-10r">
            <a ng-if="permissions_list.edit_access" class="btn btn-primary btn-outline" href="{{url('/addBug')}}">Add Bug</a>
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
                    <li class="nav-item active">
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
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">SanTrack</a></li>
                <li class="breadcrumb-item active">Projects</li>

                <!-- Breadcrumb Menu-->
                <li class="breadcrumb-menu d-md-down-none margin-5t">
                 <!-- <div class="btn-group" role="group" aria-label="Button group">
                    <a class="btn" href="#"><i class="icon-speech"></i></a>
                    <a class="btn" href="./"><i class="icon-graph"></i> &nbsp;Dashboard</a>
                    <a class=btn"" href="#"><i class="icon-settings"></i> &nbsp;Settings</a>
                  </div>-->
                    <input ng-if="permissions_list.view_access == true" type="button" class="btn btn-primary btn-outline"  value="Add Project" ng-click="addProject()">
                    <input ng-if="permissions_list.view_access == true" type="button" class="btn btn-success btn-outline"  value="Add Sprint" ng-click="addSprint()">
                </li>
            </ol>
            <div class="container-fluid list-view">
                <div class="animated fadeIn">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="cart margin-20b">
                                <div class="card-header">
                                  Projects
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered margin-0b">
                                            <thead>
                                                <th>Project</th>
                                                <th>Description</th>
                                                <th>Status</th>
                                                <th>Start</th>
                                                <th>End</th>
                                                <th>Release</th>
                                                <th>Sprints</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody ng-init="highlight_row = 1">
                                                <tr ng-repeat="project in projects track by $index" ng-click="highlightRow($index)" ng-class="{true: 'selected'}[highlightRow == $index]">

                                                     <td>
                                                         <a ng-if="project.edit_access == true" ng-click="viewProjectInModal(project.id)">@{{project.name}}</a>
                                                         <span ng-if="project.edit_access != true">@{{project.name}}</span>
                                                     </td>
                                                     <td>@{{project.description}}</td>
                                                     <td>@{{projectStatuses[project.status]}}</td>
                                                     <td>@{{project.start_date|dateFilter}}</td>
                                                     <td>@{{project.end_date|dateFilter}}</td>
                                                     <td>@{{project.release_date|dateFilter}}</td>
                                                     <td><a ng-if="project.view_access == true" href="javascript:void(0);" ng-click="getProjectSprints(project.id)">Sprints</a></td>
                                                     <td><a ng-if="project.delete_access == true" ng-click = "deleteProject(project,projects)"><i class="mdi mdi-delete"></i></a></td>
                                                </tr>
                                                <tr ng-show="!(projects).length">
                                                  <td align="center" colspan="8"> No data found </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- {!!$projectsData->render()!!} -->
                            {{$projectsData->links("pagination::bootstrap-4")}}
                        </div>
                        <div class="col-md-12">
                            <div class="cart">
                                <div class="card-header">
                                  Sprints
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered margin-0b">
                                            <thead>
                                                <th>Sprint</th>
                                                <th>Status</th>
                                                <th>Start</th>
                                                <th>End</th>
                                                <th>Completion Date</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="sprint in sprints track by $index">
                                                     <td><a ng-click="viewSprintInModal(sprint.id)">@{{sprint.name}}</a></td>
                                                     <td>@{{sprintStatuses[sprint.status]}}</td>
                                                     <td>@{{sprint.start_date|dateFilter}}</td>
                                                     <td>@{{sprint.end_date|dateFilter}}</td>
                                                     <td>@{{sprint.completed_date|dateFilter}}</td>
                                                     <td><a  ng-click = "deleteSprint(sprint,sprints)"><i class="mdi mdi-delete"></i></a></td>
                                                </tr>
                                                <tr ng-show="!(sprints).length">
                                                  <td align="center" colspan="6"> No data found </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
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


<script type="text/ng-template" id="SendColumnModal.html">
    <div class="modal-header">
        <a class="close" data-dismiss="modal" ng-click="closeModal()">&times;</a>
        <h4  id="modal-title" class="modal-title">
            Add Project
        </h4>
        <!-- <span><i class="mdi mdi-close" ng-click="closeModal()"></i></span> -->
    </div>
        <div  id="modal-body" class="modal-body">
            <form  name="projectForm" novalidate>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Project Name</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name='name' ng-class="{true: 'error'}[submitted && projectForm.name.$invalid]"  ng-model="project.name" placeholder="Project Name"  required >
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Project Status</label>
                        </div>
                        <div class="col-md-8">
                            <select ng-model='project.status' name='status' ng-class="{true: 'error'}[submitted && projectForm.status.$invalid]"  convert-to-number class="form-control" required>
                                <option value="">Status</option>
                                <option ng-repeat='projectStatus in projectStatuses ' value="@{{projectStatus.id}}" >@{{projectStatus.name}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Start Date</label>
                        </div>
                        <div class="col-md-8">
                            <md-datepicker class="form-control" ng-model="project.start_date" name='start_date' ng-class="{true: 'error'}[submitted && projectForm.start_date.$invalid]"  required></md-datepicker>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">End Date</label>
                        </div>
                        <div class="col-md-8">
                                <md-datepicker class="form-control" ng-model="project.end_date" name='end_date' ng-class="{true: 'error'}[submitted && projectForm.end_date.$invalid]"  required></md-datepicker>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Release Date</label>
                        </div>
                        <div class="col-md-8">
                                <md-datepicker class="form-control" 
                                ng-model="project.release_date" name='release_date' ng-class="{true: 'error'}[submitted && projectForm.release_date.$invalid]"  > </md-datepicker>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Description</label>
                        </div>
                        <div class="col-md-8">
                            <textarea ng-model="project.description"  name='description' ng-class="{true: 'error'}[submitted && projectForm.description.$invalid]" class="form-control" placeholder="Description" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            <span ng-repeat = "(key , error) in errors"><sup>*</sup>@{{error}}</span>
                        </div>
                    </div>
                </div>

        </div>
        <div class="modal-footer">
            <input type="button" ng-click="saveProject(project)" class="btn btn-primary btn-outline" value="Save">
            <input type="button" class="btn btn-danger btn-outline" value="Close" ng-click="closeModal()">
        </div>
    </from>
</script>
<style>
.md-datepicker-calendar-pane{
z-index: 1200;
}
.error.form-control {
  border:1px solid red;
}
</style>
<script type="text/ng-template" id="SprintModal.html">
    <div class="modal-header">
        <a class="close" data-dismiss="modal" ng-click="closeModal()">&times;</a>
        <h4  id="Sprintmodal-title" class="modal-title">
            Add Sprint
        </h4>
    </div>
    <form  name="sprintForm" novalidate>
        <div  id="Sprintmodal-body" class="modal-body">
                <div class="form-group">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Sprint Name</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" ng-model="sprint.name" name='name' ng-class="{true: 'error'}[submitted && sprintForm.name.$invalid]" class="form-control"  required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Projects</label>
                        </div>
                        <div class="col-md-8">
                            <select ng-model='sprint.project_id'  name='project_id' ng-class="{true: 'error'}[submitted && sprintForm.project_id.$invalid]" convert-to-number class="form-control" required>
                                <option value="">Projects</option>
                                <option ng-repeat='project in projects ' value="@{{project.id}}" >@{{project.name}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                        </div>
                        <div class="col-md-8">
                            <select ng-model='sprint.status' name='status' ng-class="{true: 'error'}[submitted && sprintForm.status.$invalid]"  convert-to-number class="form-control" required>
                                <option value="">Status</option>
                                <option ng-repeat='status in statuses ' value="@{{status.id}}" >@{{status.name}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Start Date</label>
                        </div>
                        <div class="col-md-8">
                            <md-datepicker class="form-control" ng-model="sprint.start_date" name='start_date' ng-class="{true: 'error'}[submitted && sprintForm.start_date.$invalid]"  required></md-datepicker>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">End Date</label>
                        </div>
                        <div class="col-md-8">
                            <md-datepicker class="form-control" ng-model="sprint.end_date" name='end_date' ng-class="{true: 'error'}[submitted && sprintForm.end_date.$invalid]"  required></md-datepicker>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Complete Date</label>
                        </div>
                        <div class="col-md-8">
                            <md-datepicker class="form-control" ng-model="sprint.completed_date" name='completed_date' ng-class="{true: 'error'}[submitted && sprintForm.completed_date.$invalid]" ></md-datepicker>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8">
                            <span ng-repeat = "(key , error) in errors"><sup>*</sup>@{{error}}</span>
                        </div>
                    </div>
                </div>
        </div>
        <div class="modal-footer">
            <input type="button" ng-click="saveSprint(sprint)" class="btn btn-primary btn-outline" value="Save">
            <input type="button" class="btn btn-danger btn-outline" value="Close" ng-click="closeModal()">
        </div>
    </from>
</script>
</html>
