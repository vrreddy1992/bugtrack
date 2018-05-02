<!DOCTYPE html>
<html ng-app = "BugTracker">
<head>
  <title>SanTrack</title>
  <link rel="icon" href="{{ asset('public/images/icon.jpg') }}" sizes="16x16 32x32" type="image/png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="{{ URL::asset('public/css/please-wait.css') }}">
    <script src="{{asset('public/js/please-wait.min.js')}}" charset="utf-8"></script>
    <link rel="stylesheet" href="{{ URL::asset('public/css/bugTracker.css') }}">
    <script type="text/javascript" src="{{URL::asset('public/js/jquery-3.2.1.min.js')}}"></script>
    <link rel="stylesheet" href="{{ URL::asset('public/css/materialdesignicons.min.css') }}">
    <script type="text/javascript" src="{{URL::asset('public/js/bower_components/tinymce/tinymce.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('public/js/bower_components/angular/angular.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('public/js/bower_components/angular-ui-tinymce/src/tinymce.js')}}"></script>
    <script src="{{URL::asset('public/js/bugTracker.js')}}" charset="utf-8"></script>
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
    <!-- <script src="{{asset('public/js/ng-confirm.js')}}" charset="utf-8"></script> -->
    <link rel="stylesheet" href="{{ URL::asset('public/css/style.css') }}">
    <!-- <script src="{{URL::asset('public/js/app.js') }}"></script> -->

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
        var allActiveProjects = <?php echo json_encode($allActiveProjects); ?>;
        var DefaultProject = <?php echo json_encode($defaultProject);?>;
        var permissions = <?php echo json_encode($permissions_list_arr);?>;
        var statusObj =  <?php echo json_encode($statusObj);?>;
    </script>
    <style type="text/css">
      .color-red{
        color: red;
      }
    </style>
</head>
<body  ng-controller="bugsListController" class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden" ng-cloak>
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
            </ul>
        </nav>
      <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>
    <main class="main">
      <!-- Breadcrumb -->
      @include('vendor.flash.message')
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">SanTrack</a></li>
            <li class="breadcrumb-item active">Bugs List</li>
            <!-- <li class="breadcrumb[item"><input type="button" onclick="getBugsList(1, true)"></li> -->

            <!-- Breadcrumb Menu-->

        </ol>
        <div class="container-fluid list-view">
            <div class="animated fadeIn">
                <div class="form-group">
                    <div class="bugsListFilter">
                        <form ng-submit="getBugsList(1, true)">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="blog">
                                        <span class="view-title" for="Project">Project</span>
                                        <select ng-model='bug.project_id' class="form-control" id="Project" >
                                            <option value="">All</option>
                                            <option ng-repeat='(key,value) in projects' value="@{{key}}" >@{{value}}</option>
                                        </select>
                                    </div>
                                    <div class="blog">
                                        <span class="view-title" for="Sprint">Sprint</span>
                                        <select ng-model='bug.sprint_id' class="form-control" id="Sprint" >
                                            <option value="">All</option>
                                            <option ng-repeat='(key,value) in sprints ' value="@{{key}}" >@{{value}}</option>
                                        </select>
                                    </div>
                                    <!-- <div class="blog">
                                        <span class="view-title" for="Module">Module</span>
                                        <select ng-model='bug.module_id' class="form-control" id="Module">
                                            <option value="">All</option>
                                            <option ng-repeat='(key,value) in modules ' value="@{{key}}" >@{{value}}</option>
                                        </select>
                                    </div> -->
                                    <div class="blog">
                                        <span class="view-title" for="Severity">Severity</span>
                                        <select ng-model='bug.severity_id'  class="form-control" id="Severity" >
                                            <option value="">All</option>
                                            <option ng-repeat='(key,value) in severities' value="@{{key}}" >@{{value}}</option>
                                        </select>
                                    </div>
                                    <div class="blog">
                                        <span class="view-title" for="Severity">Status</span>
<!--                                         <select ng-model='bug.status_id' class="form-control" id="Severity" ng-change="getBugsList(1, true)">
                                            <option value="">All</option>
                                            <option ng-repeat='(key,value) in bugstatus'   value="@{{key}}" >@{{value}}</option>
                                        </select> -->
                                        <div ng-dropdown-multiselect="" options="statusOptions" selected-model="filter_status" extra-settings="statusSettings"></div>
                                    </div>
                                    <div class="blog">
                                        <span class="view-title" for="Severity">Assigned To</span>
                                        <select ng-model='bug.assigned_to' class="form-control" id="Severity" >
                                            <option value="">All</option>
                                            <option ng-repeat='(key,value) in users' value="@{{key}}" >@{{value}}</option>
                                        </select>
                                    </div>
                                    <div class="blog">
                                        <span class="view-title" for="Severity">Reported By</span>
                                        <select ng-model='bug.reported_by' class="form-control" id="Severity" >
                                            <option value="">All</option>
                                            <option ng-repeat='(key,value) in users' value="@{{key}}" >@{{value}}</option>
                                        </select>
                                    </div>
                                    <div class="margin-17t">
                                        <input type="submit" class="btn btn-primary btn-outline" name="submit" value="Filter">
                                        <input type="button" class="btn btn-warning btn-outline" ng-click="clearFilter()" value="Clear Filter">
                                        <input type="button" class="btn btn-success btn-outline"  value="Export to Excel" ng-click="bugsListDownload()">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="form-group margin-15t">
                    <div class="row">
                        <div class="col-md-12">
                          <div class="border-main">
                            <div class="row">
                              <div class="col-md-6 padding-0r" ng-show="bulkActions">
                                <div class="bulkActions form-group" >
                                  <div class="row">
                                    <div class="col-md-4 padding-0r">
                                      <span class="batch-arrow">
                                          <i class="mdi mdi-subdirectory-arrow-right mdi-rotate-90"></i>
                                      </span>
                                      <span class="dropdown batch-actions" uib-dropdown>                    
                                        <a class="dropdown-toggle" uib-dropdown-toggle>Bulk Actions<i class="mdi mdi-chevron-down"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-breadcrumb dropdown-menu-left" uib-dropdown-menu>
                                          <li>
                                            <a ng-click = "deleteMultiple()"><i class="mdi mdi-delete"></i> Delete</a>
                                          </li>    
                                        </ul>
                                      </span>
                                    </div>                                  
                                    <div class="col-md-4 padding-0r">
                                   <!--  <label>Projects</label> -->
                                      <select  ng-model='bulkAction_project' convert-to-number  class="breadcrumb-item form-control" ng-change="changeProject()">
                                      <option value="">Select Project</option>
                                      <option ng-repeat='activeProject in activeProjects' value="@{{activeProject.id}}" >@{{activeProject.name}}</option>
                                      </select>
                                    </div>
                                  <div class="col-md-4">
                                    <!-- <label>Sprints</label> -->
                                      <select id="Sprint" ng-model='bulkAction_sprint' name="sprint"  convert-to-number class="form-control" ng-change="changeSprint()">
                                        <option value="">Select Sprint</option>
                                        <option ng-repeat='(key,value) in sprints ' value="@{{key}}" >@{{value}}</option>
                                    </select>
                                  </div>
                                  </div>
                                  </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <input type="text" class="form-control form-rounded" ng-model="search" ng-change="searchBug(search)" placeholder="Search Bugs">
                                </div>
                              </div>
                              <div class="col-md-6">
                                
                              </div>
                            </div>
                          </div>
                            <div class="table-responsive">
                                 <table class="table table-bordered">
                                    <thead>
                                        <th><input type="checkbox" ng-model="selectedAll" ng-click="checkAll()"/></th>
                                        <th>BUG-ID</th>
                                        <th>Title</th>
                                        <th>Sprint</th>
                                        <th>Severity</th>
                                        <th>Status</th>
                                        <!-- <th>Module</th> -->
                                        <th>Reported By</th>
                                        <th>Assigned To</th>
                                        <th>Created On</th>
                                        <th>Updated On</th>
                                        <th>Actions</th>
                                    </thead>
                                    <tbody>
                                        <tr dir-paginate="bug in bugs|itemsPerPage: itemsPerPage" total-items="total_bugs_count" current-page="currentPage">
                                            <td><input type="checkbox" name="type" ng-model="bulk[bug.id]"  ng-checked="bulk[bug]" ng-change="showBulkOptions()" ng-click="showBulkOptions()"></td>
                                             <!-- <td><input type="checkbox" name="type" ng-model="bulk[bug.id]" ng-change="showBulkOptions()"></td> -->
                                             <!-- <td><input type="checkbox" name="type" ng-change="selectChkBox(bug)" /></td> -->
                                             <td>
                                                 <a ng-if="bug.view_access" href="{{url('/viewBug')}}/@{{bug.id}}">BG - @{{bug.bug_code}}</a>
                                                 <span ng-if="bug.view_access == false">BG-@{{bug.bug_code}}</span>
                                             </td>

                                             <td style="white-space: normal;">@{{bug.title}}</td>
                                             <td>@{{bug.sprint_id}}</td>
                                             <td ng-class="{'primary-color':bug.severity_id === 'Blocker','orange-color':bug.severity_id === 'Critical','red-color':bug.severity_id === 'Major','color-red':bug.severity_id === 'Normal','seagreen-color':bug.severity_id === 'Minor','green-color':bug.severity_id === 'Enhancement'}">@{{bug.severity_id}}</td>
                                             <!-- <td>@{{bug.status_id}}</td> -->
                                             <td width="140px" >
                                               <select ng-model='status.id' class="form-control" id="Severity" 
                                               ng-init="status.id = bug.status_id" convert-to-number
                                                ng-change="changeStatus(status,bug.id)" ng-disabled="!bug.edit_access">
                                                   <option ng-repeat='(key,value) in bugstatus'   value="@{{key}}" >@{{value}}</option>
                                               </select>
                                             </td>
                                             <td>@{{bug.reported_by}}</td>
                                             <td>@{{bug.assigned_to}}</td>
                                             <td>@{{bug.created_at|dateFilter}}</td>
                                             <td>@{{bug.updated_at|dateFilter}}</td>
                                             <td>
                                                 <!-- <a ng-if="bug.view_access" href="{{url('/viewBug')}}/@{{bug.id}}"><i class="mdi mdi-eye"></i></a>&nbsp;&nbsp; -->
                                                 <a ng-if="bug.edit_access" href="{{url('/editBug')}}/@{{bug.id}}"><i class="mdi mdi-pencil"></i></a>&nbsp;
                                                 <a ng-if="bug.delete_access"  ng-click = "deleteBug(bug.id,bug,bugs)"><i class="mdi mdi-delete"></i></a>
                                             </td>
                                        </tr>
                                        <tr ng-show="!(bugs).length">
                                            <td align="center" colspan="11"> No data found </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                    			<dir-pagination-controls table-info="true"></dir-pagination-controls>
                    		</div>

                    		<div class="col-md-6 text-right">
                    			<dir-pagination-controls max-size="5" direction-links="true" boundary-links="true" on-page-change="getBugsList(newPageNumber)">
                    			</dir-pagination-controls>
                    		</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
