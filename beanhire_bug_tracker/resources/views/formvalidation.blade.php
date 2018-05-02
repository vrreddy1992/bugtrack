<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
                <!-- CSS ===================== -->
           <!-- load bootstrap -->
           <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
           <style>
               body    { padding-top:30px; }
           </style>

           <!-- JS ===================== -->
           <!-- load angular -->
           <script src="http://code.angularjs.org/1.2.6/angular.js"></script>
           <script src="{{URL::asset('js/validationTest.js')}}"></script>
    </head>
    <style >
    .form.submitted .ng-invalid
    {
        border:1px solid #f00;
    }
    </style>
    <body ng-app="validationApp" ng-controller="mainController">
        <div class="container">
        <div class="col-sm-8 col-sm-offset-2">

        <!-- PAGE HEADER -->
        <div class="page-header"><h1>AngularJS Form Validation</h1></div>

        <!-- FORM -->
        <!-- pass in the variable if our form is valid or invalid -->
        <form name="userForm" ng-submit="submitForm(userForm.$valid)" > <!-- novalidate prevents HTML5 validation since we will be validating ourselves -->

            <!-- NAME -->
<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control" ng-model="name" required>
</div>

            <!-- USERNAME -->
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" ng-model="user.username" ng-minlength="3" ng-maxlength="8">
            </div>

            <!-- EMAIL -->
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" ng-model="email">
            </div>

            <!-- SUBMIT BUTTON -->
            <button type="submit"  class="btn btn-primary" ng-disabled="userForm.$invalid">Submit</button>

        </form>

        </div><!-- col-sm-8 -->
        </div><!-- /container -->
    </body>
</html>
