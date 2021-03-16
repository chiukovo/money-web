<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/png" sizes="16x16" href="/img/icon.png">
  <title>{{ env('APP_NAME') }}</title>
  <!-- Custom CSS -->
  <link rel="stylesheet" href="/assets/fonts/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="/assets/extra-libs/multicheck/multicheck.css">
  <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
  <link href="/dist/css/style.min.css" rel="stylesheet">
  <link href="/assets/libs/toastr/build/toastr.min.css" rel="stylesheet">
  <link href="/dist/css/custom.css" rel="stylesheet">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  <script src="/dist/js/jquery.min.js"></script>
  <script src="/dist/js/vue.min.js"></script>
  <script src="/dist/js/bluebird.min.js"></script>
  <style>
    [v-cloak] {
      display: none;
    }
  </style>
</head>

<body>
  <div id="main-wrapper">
    <header class="topbar" data-navbarbg="skin5">
      <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header" data-logobg="skin5">
          <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
          <a class="navbar-brand" href="index.html">
            <b class="logo-icon">
              <a class="sidebartoggler waves-effect waves-dark p-l-20 p-r-20" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a>
            </b>
            <span class="logo-text">
              「{{ env('APP_NAME') }}」管理後臺
            </span>
          </a>
        </div>
        <div class="navbar-collapse collapse" id="navbarSupportedContent">
          <ul class="navbar-nav float-left mr-auto">
          </ul>
          <ul class="navbar-nav float-right">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user m-r-10"></i>Welcome Back !<span class="m-l-10">{{ adminLoginInfo()->name }}</span></a>
              <div class="dropdown-menu dropdown-menu-right animated">
                <a class="dropdown-item" href="/admin/logout"><i class="fa fa-power-off m-r-5 m-l-5"></i> 登出系統</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    @include('admin.layouts.left-sidebar')
    <div id="page-wrapper" class="page-wrapper">
      @yield('content')
      <footer class="footer text-center">
        系統版本 <span class="badge badge-pill badge-dark">{{ config('app.version') }}</span>
      </footer>
    </div>
  </div>

  <!-- Bootstrap tether Core JavaScript -->
  <script src="/assets/libs/popper.js/dist/umd/popper.min.js"></script>
  <script src="/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- slimscrollbar scrollbar JavaScript -->
  <script src="/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
  <script src="/assets/extra-libs/sparkline/sparkline.js"></script>
  <!--Wave Effects -->
  <script src="/dist/js/waves.js"></script>
  <!--Menu sidebar -->
  <script src="/dist/js/sidebarmenu.js"></script>
  <!--Custom JavaScript -->
  <script src="/dist/js/custom.min.js"></script>
  <script src="/assets/libs/toastr/build/toastr.min.js"></script>
  <script>
    $(function() {
      toastr.options = {
        "timeOut": "1000",
        "showEasing": "swing", // 顯示動畫時間曲線
        "showMethod": "fadeIn", // 顯示動畫效果
        "preventDuplicates": false,
        "preventDuplicates": false,
        "newestOnTop": true
      }
    });
  </script>
  @yield('page-js')
</body>

</html>