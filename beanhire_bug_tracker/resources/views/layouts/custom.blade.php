
<!DOCTYPE html>
<html ng-app = "BugTracker">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="{{ URL::asset('css/bugTracker.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/materialdesignicons.min.css') }}">
    <script type="text/javascript" src="{{URL::asset('js/bower_components/tinymce/tinymce.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('js/bower_components/angular/angular.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('js/bower_components/angular-ui-tinymce/src/tinymce.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('js/jquery-3.2.1.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('js/alasql.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('js/xlsx.core.min.js')}}"></script>
    <link type="text/css" rel="stylesheet" href="{{URL::asset('css/bootstrap.min.css')}}">
    <script type="text/javascript" src="{{URL::asset('js/ui-bootstrap-tpls-2.5.0.min.js')}}" charset="utf-8"></script>
    <script src="{{URL::asset('js/bower_components/angular-material/angular-material.min.js')}}" charset="utf-8"></script>
    <link rel="stylesheet" href="{{URL::asset('js/bower_components/angular-material/angular-material.min.css')}}">
    <script src="{{URL::asset('js/bower_components/angular-animate/angular-animate.min.js')}}" charset="utf-8"></script>
    <script src="{{URL::asset('js/bower_components/angular-aria/angular-aria.min.js')}}" charset="utf-8"></script>
    <script src="{{URL::asset('js/bower_components/angular-messages/angular-messages.min.js')}}" charset="utf-8"></script>
    <script src="{{URL::asset('js/app-core.js')}}" charset="utf-8"></script>
    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
    <!-- <script type="text/javascript" src="{{URL::asset('js/bower_components/sweetalert.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('js/bower_components/SweetAlert.min.js')}}"></script>
    <link rel="stylesheet" href="{{URL::asset('js/bower_components/sweetalert.css')}}"> -->
    <script src="{{URL::asset('js/bugTracker.js')}}" charset="utf-8"></script>
    <script type="text/javascript">
        var bugs = <?php echo  json_encode($bugs); ?>;
        var projects = <?php echo json_encode($projects); ?>;
        var modules = <?php echo json_encode($modules); ?>;
        var sprints = <?php echo json_encode($sprints); ?>;
        var bugstatus = <?php echo json_encode($status); ?>;
        var severity = <?php echo json_encode($severity); ?>;
        var users = <?php echo json_encode($users); ?>;

    </script>
</head>
<body  ng-controller="bugsListController" class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
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
        <ul class="nav navbar-nav d-md-down-none">
          <li class="nav-item px-3">
            <a class="nav-link" href="#">Dashboard</a>
          </li>
          <li class="nav-item px-3">
            <a class="nav-link" href="#">Users</a>
          </li>
          <li class="nav-item px-3">
            <a class="nav-link" href="#">Settings</a>
          </li>
        </ul>
        <ul class="nav navbar-nav ml-auto">
          <!-- <li class="nav-item d-md-down-none">
            <a class="nav-link" href="#"><i class="icon-bell"></i><span class="badge badge-pill badge-danger">5</span></a>
          </li>
          <li class="nav-item d-md-down-none">
            <a class="nav-link" href="#"><i class="icon-list"></i></a>
          </li>
          <li class="nav-item d-md-down-none">
            <a class="nav-link" href="#"><i class="icon-location-pin"></i></a>
          </li> -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
              <img src="../images/avatars/6.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
              <span class="d-md-down-none">admin</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="dropdown-header text-center">
                <strong>Account</strong>
              </div>
              <!-- <a class="dropdown-item" href="#"><i class="fa fa-bell-o"></i> Updates<span class="badge badge-info">42</span></a>
              <a class="dropdown-item" href="#"><i class="fa fa-envelope-o"></i> Messages<span class="badge badge-success">42</span></a>
              <a class="dropdown-item" href="#"><i class="fa fa-tasks"></i> Tasks<span class="badge badge-danger">42</span></a>
              <a class="dropdown-item" href="#"><i class="fa fa-comments"></i> Comments<span class="badge badge-warning">42</span></a>
              <div class="dropdown-header text-center">
                <strong>Settings</strong>
              </div>
              <a class="dropdown-item" href="#"><i class="fa fa-user"></i> Profile</a>
              <a class="dropdown-item" href="#"><i class="fa fa-wrench"></i> Settings</a>
              <a class="dropdown-item" href="#"><i class="fa fa-usd"></i> Payments<span class="badge badge-secondary">42</span></a>
              <a class="dropdown-item" href="#"><i class="fa fa-file"></i> Projects<span class="badge badge-primary">42</span></a>
              <div class="divider"></div> -->
              <a class="dropdown-item" href="#"><i class="fa fa-shield"></i> Lock Account</a>
              <a class="dropdown-item" href="#"><i class="fa fa-lock"></i> Logout</a>
            </div>
          </li>
        </ul>
    <!-- <button class="navbar-toggler aside-menu-toggler" type="button">
      <span class="navbar-toggler-icon">
          <i class="mdi mdi-menu"></i>
      </span>
    </button> -->

  </header>
  <div class="app-body">
    <div class="sidebar">
        <nav class="sidebar-nav">
            <ul class="nav">
                <!-- <li class="nav-item active">
                    <a class="nav-link" href="#!">Home</a>
                </li>  -->
                <li class="nav-item">
                    <a class="nav-link" href="/viewBugs">Bugs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/addBug">Add Bug</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/projects">Projects</a>
                </li>
            </ul>
        </nav>
      <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>

</body>
</html>
