<!DOCTYPE html>
<html ng-app="BugTracker">
    <head>
        <meta charset="utf-8">
        <title>SanTrack</title>
        <link rel="stylesheet" href="{{ URL::asset('public/css/bugTracker.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('public/css/materialdesignicons.min.css') }}">
        <script type="text/javascript" src="{{URL::asset('public/js/bower_components/tinymce/tinymce.js')}}"></script>
        <script type="text/javascript" src="{{URL::asset('public/js/bower_components/angular/angular.js')}}"></script>
        <script type="text/javascript" src="{{URL::asset('public/js/bower_components/angular-ui-tinymce/src/tinymce.js')}}"></script>
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
        <link rel="stylesheet" href="{{ URL::asset('public/css/style.css') }}">
        <script src="{{URL::asset('public/js/bugTracker.js')}}" charset="utf-8"></script>
        <script type="text/javascript" src="{{URL::asset('public/js/angularjs-dropdown-multiselect.js')}}"></script>

        <script src="{{asset('public/js/paginate.js')}}" charset="utf-8"></script>
        <script type="text/javascript">
            var root_url = "{{ url('/') }}"+"/";
            var Roles = <?php echo json_encode($roles_list);?>;
            var Modules = <?php echo json_encode($modules_list);?>;
        </script>
    </head>
    <body ng-controller="permisssionsController">
        <table class="table" width="100%" border="1" cellpadding="0" cellspacing="0">
            <thead>
                <th>Role</th>
                <th>Created By</th>
                <th>Last Updated By</th>
                <th>Comments</th>
            </thead>
            <tbody>
                <tr ng-repeat="role in roles">
                    <td ng-click="setUserID(role.id)">@{{role.name}}</td>
                    <td>@{{role.created_at}}</td>
                    <td>@{{role.updated_at}}</td>
                    <td>hi</td>
                </tr>
            </tbody>
        </table>
        @include('rolesList')
    </body>
</html>
