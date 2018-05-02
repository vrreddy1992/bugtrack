@extends('layouts.template')
@section('content')
<script type="text/javascript">
	var SubModuleID = <?php echo json_encode($id);?>;
	var defaultData = <?php echo json_encode($defaultData);?>;
	var testCaseSteps = <?php echo json_encode($testCaseSteps); ?>;
	var testCaseStepResults = <?php echo json_encode($testCaseStepResults); ?>;
	var stepCount = <?php echo json_encode($stepsCount); ?>;
	var type = <?php echo json_encode($operationType);?>;
	var testCaseID = <?php echo json_encode($testCaseID); ?>;
</script>
<div ng-controller="AddTestCaseController"  class="table-responsive">
	<table class="table table-bordered">
		<tr>
			<td><label>Project</label></td>
			<td><input type="text" ng-model="testCase.project_name" disabled></td>
			<input type="hidden" ng-model="testCase.project_id">			
		</tr>
		<tr>
			<td><label>Module</label></td>
			<td><input type="text" ng-model="testCase.module_name" disabled></td>
			<input type="hidden" ng-model="testCase.module_id">			
		</tr>
		<tr>
			<td><label>Sub-Module</label></td>
			<td><input type="text" ng-model="testCase.subModule_name" disabled></td>	
			<input type="hidden" ng-model="testCase.subModule_id">	
		</tr>
		<tr>
			<td><label>Test Case ID</label></td>
			<td><input type="text" ng-model="testCase.testcase_id" disabled></td>
		</tr>
		<tr>
			<td><label>Title</label></td>
			<td><input type="text" name="title" ng-model="testCase.title"  maxlength="200"></td>
		</tr>
		<tr>
			<td><label>Pre-Conditions</label></td>
			<td><textarea name="preconditions" ng-model="testCase.preconditions"></textarea></td>
		</tr>
		<tr>
			<td>Steps</td>
			<td>Expected Result</td>
		</tr>
		<tr>
			<td><textarea ng-model="testCase.step[0]"></textarea></td>
			<td><textarea ng-model="testCase.expectedResult[0]"></textarea></td>	
		</tr>
		<tr>
			<td><textarea ng-model="testCase.step[1]"></textarea></td>
			<td><textarea ng-model="testCase.expectedResult[1]"></textarea></td>	
		</tr>
		<tr>
			<td><textarea ng-model="testCase.step[2]"></textarea></td>
			<td><textarea ng-model="testCase.expectedResult[2]"></textarea></td>	
		</tr>
		<tr ng-repeat="step in steps">
			<td><textarea ng-model="testCase.step[step]"></textarea></td>
			<td><textarea ng-model="testCase.expectedResult[step]"></textarea><input  type="button" ng-click="deleteRow(step,steps)" value="Delete"></td>			
		</tr>
		<tr>
			<td></td>
			<td><input type="button" ng-click="addStep()" value="Add Step"></td>			
		</tr>
		<tr>
			<td></td>
			<td><input type="button" ng-click="save(testCase)" value="Save"></td>			
		</tr>
	</table>
</div>
@endsection
