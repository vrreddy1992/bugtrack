@extends('layouts.template')
@section('content')
<script type="text/javascript">
	var submoduleID = <?php echo json_encode($id);?>;
	var Users       = <?php echo json_encode($users); ?>;
</script>
<div class="container-fluid list-view" ng-controller="TestCasesController">
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
                    </div>
                  </div>
                    <div class="table-responsive">
                         <table class="table table-bordered">
                            <thead>
                                <th></th>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Created By</th>
                                <th>Created Date</th>
                                <th>Updated By</th>
                                <th>Updated Date</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                <tr  dir-paginate = "testcase in testcases|itemsPerPage: itemsPerPage" total-items="total_testcases_count" current-page="currentPage">
                                	 <td></td>
                                     <td>@{{testcase.testcase_id}}</td>
                                     <td>@{{testcase.title}}</td>
                                     <td>@{{users[testcase.created_by]}}</td>
                                     <td>@{{testcase.created_at|dateFilter}}</td>
                                     <td>@{{users[testcase.updated_by]}}</td>
                                     <td>@{{testcase.updated_at|dateFilter}}</td>
                                     <td>
                                     	<!-- href="{{url('/testcase')}}/@{{submodule.id}}" -->
                                         <!-- <a ng-if="bug.view_access" href="{{url('/viewBug')}}/@{{bug.id}}"><i class="mdi mdi-eye"></i></a>&nbsp;&nbsp; -->                 
                                         <a href="{{url('/editTestcase')}}/@{{testcase.id}}"><i class="mdi mdi-pencil"></i></a>&nbsp;
                                         <a ng-click = ""><i class="mdi mdi-delete"></i></a>
                                     </td>
                                </tr>
                                <tr ng-show="!(testcases).length">
                                    <td align="center" colspan="11"> No data found </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="col-md-6">
                			<dir-pagination-controls table-info="true"></dir-pagination-controls>
                		</div>

                		<div class="col-md-6 text-right">
                			<dir-pagination-controls max-size="5" direction-links="true" boundary-links="true" on-page-change="getTestcasesList(newPageNumber)">
                			</dir-pagination-controls>
                		</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection