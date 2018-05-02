@extends('layouts.template')
@section('content')
<script type="text/javascript">
	var Modules = <?php echo json_encode($modules); ?>;
	var Sprints = <?php echo json_encode($sprints); ?>;
	var Users   = <?php echo json_encode($users); ?>;
</script>
<div class="container-fluid list-view" ng-controller="ModulesController">
    <div class="animated fadeIn">
        <div class="form-group">
            <div class="bugsListFilter">
                <form ng-submit="getBugsList(bug)">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="blog">
                                <span class="view-title" for="Project">Project</span>
                                <select ng-model='bug.project_id' class="form-control" id="Project">
                                    <option value="">All</option>
                                    <option ng-repeat='(key,value) in projects' value="@{{key}}" >@{{value}}</option>
                                </select>
                            </div>
                            <div class="blog">
                                <span class="view-title" for="Sprint">Sprint</span>
                                <select ng-model='bug.sprint_id' class="form-control" id="Sprint">
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
                                <span class="view-title" for="Severity">Module</span>
                                <select ng-model='bug.severity_id'  class="form-control" id="Severity">
                                    <option value="">All</option>
                                    <option ng-repeat='(key,value) in severities' value="@{{key}}" >@{{value}}</option>
                                </select>
                            </div>
                            <div class="blog">
                                <span class="view-title" for="Severity">Created By</span>
                                <select ng-model='bug.status_id' class="form-control" id="Severity">
                                    <option value="">All</option>
                                    <option ng-repeat='(key,value) in bugstatus'   value="@{{key}}" >@{{value}}</option>
                                </select>
                            </div>
                            <div class="blog">
                                <span class="view-title" for="Severity">Created Date</span>
                                <select ng-model='bug.assigned_to' class="form-control" id="Severity">
                                    <option value="">All</option>
                                    <option ng-repeat='(key,value) in users' value="@{{key}}" >@{{value}}</option>
                                </select>
                            </div>
                            <div class="blog">
                                <span class="view-title" for="Severity">Updated By</span>
                                <select ng-model='bug.assigned_to' class="form-control" id="Severity">
                                    <option value="">All</option>
                                    <option ng-repeat='(key,value) in users' value="@{{key}}" >@{{value}}</option>
                                </select>
                            </div>
                            <div class="blog">
                                <span class="view-title" for="Severity">Updated Date</span>
                                <select ng-model='bug.assigned_to' class="form-control" id="Severity">
                                    <option value="">All</option>
                                    <option ng-repeat='(key,value) in users' value="@{{key}}" >@{{value}}</option>
                                </select>
                            </div>
                            <div class="margin-17t">
                                <input type="submit" class="btn btn-primary btn-outline" name="submit" value="Filter">
                                <input type="button" class="btn btn-warning btn-outline" ng-click="clearFilter()" value="Clear Filter">
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
                                <a class="dropdown-toggle" uib-dropdown-toggle>Bulck Actions<i class="mdi mdi-chevron-down"></i></a>
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
<!--                       <div class="col-md-3">
                        <div class="form-group">
                          <input type="text" class="form-control form-rounded" ng-model="search" ng-change="searchBug(search)" placeholder="Search Bugs">
                        </div>
                      </div> -->
                      <div class="col-md-6">
                        <input type="button" ng-click="addModule()" value="Add Module" class="btn btn-primary btn-outline">
                      </div>
                    </div>
                  </div>
                    <div class="table-responsive">
                         <table class="table table-bordered">
                            <thead>
                                <th></th>
                                <th>Module</th>
                                <th>TC Count</th>
                                <th>Sprint</th>
                                <th>Created By</th>
                                <th>Created Date</th>
                                <th>Updated By</th>
                                <th>Updated Date</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                <tr ng-repeat="module in modules">
                                     <td></td>
                                     <td><a href="{{url('/sub_modules')}}/@{{module.id}}">@{{module.name}}</a></td>
                                     <td>N/A</td>
                                     <td>@{{sprints[module.sprint_id]}}</td>
                                     <td>@{{users[module.author]}}</td>
                                     <td>@{{module.created_at|dateFilter}}</td>
                                     <td></td>
                                     <td>@{{module.updated_at|dateFilter}}</td>
                                     <td>
                                         <!-- <a ng-if="bug.view_access" href="{{url('/viewBug')}}/@{{bug.id}}"><i class="mdi mdi-eye"></i></a>&nbsp;&nbsp; -->
                                         <a href="javascript:;" ng-click="viewModule(module.id)"><i class="mdi mdi-pencil"></i></a>&nbsp;
                                         <a ng-click = "deleteModule(module,modules)"><i class="mdi mdi-delete"></i></a>
                                     </td>
                                </tr>
                                <tr ng-show="!(modules).length">
                                    <td align="center" colspan="11"> No data found </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/ng-template" id="ModuleModal.html">
    <div class="modal-header">
        <a class="close" data-dismiss="modal" ng-click="closeModal()">&times;</a>
        <h4  id="Modulemodal-title" class="modal-title">
            Add Module
        </h4>
    </div>
    <form  name="moduleForm" novalidate>
        <div  id="Modulemodal-body" class="modal-body">
                <div class="form-group">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Project:</label>
                            </div>
                            <div class="col-md-8">
                            	<input type="text" ng-model="module.project_name" name="project_id" class="form-control">
                            	<input type="hidden" ng-model="module.project_id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Sprint:</label>
                        </div>
                        <div class="col-md-8">
                            <select ng-model='module.sprint_id'  name='sprint_id' ng-class="{true: 'error'}[submitted && moduleForm.sprint_id.$invalid]" convert-to-number class="form-control">
                                <option value="">Select</option>
                                <option ng-repeat="(key,value) in sprints" value="@{{key}}">@{{value}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Module Name:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" ng-class="{true: 'error'}[submitted && moduleForm.name.$invalid]" ng-model="module.name" name="name" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Task Prefix:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" ng-model="module.iD_Prefix" name="iD_Prefix" >
                            <input type="text" value="_01" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Documents:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="file" ng-model="module.docs">
                        </div>
                    </div>
                </div>
        </div>
        <div class="modal-footer">
            <input type="button" ng-click="saveModule(module)" class="btn btn-primary btn-outline" value="Save">
            <input type="button" class="btn btn-danger btn-outline" value="Close" ng-click="closeModal()">
        </div>
    </from>
</script>
@endsection