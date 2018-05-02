<div class="panel panel-default">
	<h6 class="panel-heading">
		<div class="row">
			<div class="col-md-6">
				<span class="panel-title">Activity</span>
			</div>
			<div class="col-md-6 text-right padding-5t">
				<a class="btn-primary-text" ng-click="getActivity(bug.id)" ng-if="!(activities).length">Show</a>
				<a class="btn-primary-text" ng-click="hideActivity()" ng-if="(activities).length">Hide</a>
			</div>
		</div>		
	</h6>
	<div class="table-responsive" ng-show="(activities).length">
		<table class="table" >
			<th>Description</th>
			<th>Updated By</th>
			<th>Updated On</th>
			<tr ng-repeat="activity in activities">
				<td>@{{activity.activity}}</td>
				<td>@{{activity.activity_by}}</td>
				<td>@{{activity.time}}</td>
			</tr>
		</table>
	</div>
</div>