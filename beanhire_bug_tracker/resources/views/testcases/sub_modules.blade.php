@extends('layouts.template')
@section('content')
<script type="text/javascript">
	var ModuleID = <?php echo json_encode($id); ?>;
	var SubModules = <?php echo json_encode($sub_modules); ?>;
	var Users   = <?php echo json_encode($users); ?>;

</script>
	<div class="container-fluid list-view" ng-controller="SubModulesController">
	    <div class="animated fadeIn">
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
	                        <input type="button" ng-click="addSubModule()" value="Add SubModule" class="btn btn-primary btn-outline">	                       
	                      </div>
	                    </div>
	                  </div>
	                    <div class="table-responsive">
	                         <table class="table table-bordered">
	                            <thead>
	                                <th></th>
	                                <th>SubModule</th>
	                                <th>TC Count</th>
	                                <th>Created By</th>
	                                <th>Created Date</th>
	                                <th>Updated By</th>
	                                <th>Updated Date</th>
	                                <th>Actions</th>
	                            </thead>
	                            <tbody>
	                                <tr ng-repeat="submodule in submodules">
	                                	 <td></td>
	                                     <td><a href="{{url('/testcases')}}/@{{submodule.id}}">@{{submodule.name}}</a></td>
	                                     <td>N/A</td>
	                                     <td>@{{users[submodule.created_by]}}</td>
	                                     <td>@{{submodule.created_at|dateFilter}}</td>
	                                     <td>@{{users[submodule.updated_by]}}</td>
	                                     <td>@{{submodule.updated_at|dateFilter}}</td>
	                                     <td>
	                                     	<!-- href="{{url('/testcase')}}/@{{submodule.id}}" -->
	                                         <!-- <a ng-if="bug.view_access" href="{{url('/viewBug')}}/@{{bug.id}}"><i class="mdi mdi-eye"></i></a>&nbsp;&nbsp; -->
	                       					 <a href="{{url('/add_testcase')}}/@{{submodule.id}}"><i class="mdi mdi-plus"></i></a>                 
	                                         <a href=""><i class="mdi mdi-pencil"></i></a>&nbsp;
	                                         <a ng-click = ""><i class="mdi mdi-delete"></i></a>
	                                     </td>
	                                </tr>
	                                <tr ng-show="!(submodules).length">
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
	<script type="text/ng-template" id="SubModuleModal.html">
	    <div class="modal-header">
	        <a class="close" data-dismiss="modal" ng-click="closeModal()">&times;</a>
	        <h4  id="SubModulemodal-title" class="modal-title">
	            Add SubModule
	        </h4>
	    </div>
	    <form  name="submoduleForm" novalidate>
	        <div  id="SubModulemodal-body" class="modal-body">
	                <div class="form-group">
	                    <div class="form-group">
	                        <div class="row">
	                            <div class="col-md-4">
	                                <label class="form-label">Sub-Module:</label>
	                            </div>
	                            <div class="col-md-8">
	                            	<input type="text" ng-model="sub_module.name" name="name" ng-class="{true: 'error'}[submitted && submoduleForm.name.$invalid]" class="form-control">
	                            </div>
	                        </div>
	                    </div>
	                    <div class="row">
	                        <div class="col-md-4">
	                            <label class="form-label">Module:</label>
	                        </div>
	                        <div class="col-md-8">
	                            <select ng-model='sub_module.module_id'  name='module_id' ng-class="{true: 'error'}[submitted && submoduleForm.module_id.$invalid]" convert-to-number class="form-control" disabled>
	                                <option value="">Select</option>
	                                <option ng-repeat="(key,value) in modules" value="@{{key}}">@{{value}}</option>
	                            </select>
	                        </div>
	                    </div>
	                </div>
	        </div>
	        <div class="modal-footer">
	            <input type="button" ng-click="saveSubModule(sub_module)" class="btn btn-primary btn-outline" value="Save">
	            <input type="button" class="btn btn-danger btn-outline" value="Close" ng-click="closeModal()">
	        </div>
	    </from>
	</script>
@endsection
