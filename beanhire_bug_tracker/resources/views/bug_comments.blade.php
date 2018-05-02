<script>
	var Comments = <?php echo  json_encode($comments); ?>;
</script>
<div>
<!-- <label><input type="checkbox" ng-model="commentshow"  name="test">Comment</label>
 -->
	<div class="panel panel-default">
		<h6 class="panel-heading">
			<div class="row">
				<div class="col-md-6">
					<span class="panel-title">Comments</span>
				</div>
				<div class="col-md-6 text-right padding-5t">
					<!-- <a href="!#" class="btn-primary-text">Add Comment</a> -->
					<!-- <input type="button" class="btn btn-primary btn-outline" value="Save" ng-click="saveComment()"> -->
					<a class="btn-primary-text" ng-click="saveComment()">Save</a>
					<!-- <a href="!#" class="btn-danger-text">Cancel</a> -->
				</div>
			</div>
			
		</h6>	
		<div class="panel-body">
			<textarea ng-model="comment" class="form-control" placeholder="Comment"></textarea>
			<div class="comments-data margin-10t" ng-repeat = "comment in comments">
				<p>@{{comment.comment}} - <span>@{{users[comment.created_by]}}</span></p>
			</div>	
		</div>
	</div>
</div>

