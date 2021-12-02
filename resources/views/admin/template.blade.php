<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('admin/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('admin/dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('admin/plugins/toastr/toastr.min.css')}}">
  @stack('css')

</head>
<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{asset('admin/dist/img/logo-punggul.png')}}" height="60" width="60">
  </div>
  
@if (Request::segment(1) != 'user')

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>

        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="{{asset('admin/dist/img/user8-128x128.jpg')}}" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>

      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>

          <!-- Notification Messages Start Here -->
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <!-- Notification Messages End Here -->

          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
      <img src="{{asset('admin/dist/img/logo-punggul.png')}}" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Admin Hotspot</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('admin/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{Auth::user()->nama}}</a>
        </div>
      </div>


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item {{Request::segment(2) == 'dashboard' ? 'menu-open' : ''}}">
            <a href="{{route('admin.dashboard')}}" class="nav-link {{Request::segment(2) == 'dashboard' ? 'active' : ''}}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          
          <li class="nav-item {{Request::segment(2) == 'pengumuman' ? 'menu-open' : ''}}">
            <a href="#" class="nav-link {{Request::segment(2) == 'pengumuman' ? 'active' : ''}}">
              <i class="nav-icon fas fa-bullhorn"></i>
              <p>
                Pengumuman
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link {{Request::segment(3) == 'aktif' ? 'active' : ''}}">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Aktif</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link {{Request::segment(3) == 'nonaktif' ? 'active' : ''}}">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Tidak Aktif</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item {{Request::segment(2) == 'account' ? 'menu-open' : ''}}">
            <a href="{{route('admin.account')}}" class="nav-link {{Request::segment(2) == 'account' ? 'active' : ''}}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Admin Account
              </p>
            </a>
          </li>

          <li class="nav-item {{Request::segment(2) == 'hotspot' ? 'menu-open' : ''}}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Hotspot User
                <i class="fas fa-angle-left right"></i>
                 <!-- <span class="badge badge-info right">6</span> -->
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('admin.user', ['user' => 'organik'])}}" class="nav-link {{Request::segment(3) == 'organik' ? 'active' : ''}}">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Warga Asli</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.user', ['user' => 'facebook'])}}" class="nav-link {{Request::segment(3) == 'facebook' ? 'active' : ''}}">
                  <i class="fab fa-facebook-square nav-icon"></i>
                  <p>Facebook</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.user', ['user' => 'google'])}}" class="nav-link {{Request::segment(3) == 'google' ? 'active' : ''}}">
                  <i class="fab fa-google-plus-g nav-icon"></i>
                  <p>Google</p>
                </a>
              </li>
            </ul>
          </li>


          <li class="nav-item {{Request::segment(2) == 'report' ? 'menu-open' : ''}}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Report
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">

              <li class="nav-item">
                <a href="{{route('admin.report.usage', ['range' => 'weekly'])}}" class="nav-link {{Request::segment(3) == 'weekly' ? 'active' : ''}}">
                  <i class="fas fa-chart-line nav-icon"></i>
                  <p>Mingguan</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{route('admin.report.usage', ['range' => 'monthly'])}}" class="nav-link {{Request::segment(3) == 'monthly' ? 'active' : ''}}">
                  <i class="fas fa-chart-line nav-icon"></i>
                  <p>Bulanan</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{route('admin.report.usage', ['range' => 'yearly'])}}" class="nav-link {{Request::segment(3) == 'yearly' ? 'active' : ''}}">
                  <i class="fas fa-chart-line nav-icon"></i>
                  <p>Tahunan</p>
                </a>
              </li>

            </ul>
          </li>

          @if(Auth::user()->role == 1)

          <li class="nav-item {{Request::segment(2) == 'mikrotik' ? 'menu-open' : ''}}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-server"></i>
              <p>
                Mikrotik Management
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">

              <li class="nav-item">
                <a href="{{route('admin.mikrotik.listGroupUser')}}" class="nav-link {{Request::segment(3) == 'kategori_user' ? 'active' : ''}}">
                  <i class="fas fa-user nav-icon"></i>
                  <p>Kategori User</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{route('admin.mikrotik.showActive')}}" class="nav-link {{Request::segment(3) == 'activeUser' ? 'active' : ''}}">
                  <i class="fas fa-users-cog nav-icon"></i>
                  <p>Hotspot User Active</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{route('admin.mikrotik.getQueue')}}" class="nav-link {{Request::segment(3) == 'queue' ? 'active' : ''}}">
                  <i class="fab fa-buffer nav-icon"></i>
                  <p>Queue</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{route('admin.mikrotik.getHotspot')}}" class="nav-link {{Request::segment(3) == 'hotspot' ? 'active' : ''}}">
                  <i class="fas fa-wifi nav-icon"></i>
                  <p>Hotspot</p>
                </a>
              </li>

            </ul>
          </li>

          @endif
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

@else

<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index3.html" class="brand-link">
    <img src="{{asset('admin/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">AdminLTE 3</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->



    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->

        <li class="nav-item {{Request::segment(2) == 'privacy-policy' ? 'active' : ''}}">
          <a href="{{route('hotspot.redirect.status')}}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Status
            </p>
          </a>
        </li>

        <li class="nav-item {{Request::segment(2) == 'privacy-policy' ? 'active' : ''}}">
          <a href="{{route('hotspot.privacy')}}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Privacy Policy
            </p>
          </a>
        </li>

        
        <li class="nav-item {{Request::segment(2) == 'terms-of-service' ? 'active' : ''}}">
          <a href="{{route('hotspot.tos')}}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Terms & Condition
            </p>
          </a>
        </li>


      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>

@endif


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">@yield('header')</h1>
                </div><!-- /.col -->
                
                @if (Request::segment(1) != 'user')

                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                  <li class="breadcrumb-item active">Dashboard v1</li>
                  </ol>
                </div><!-- /.col -->
                  
                @endif
               
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

    @yield('body')

    </div>
    <!-- /.content-wrapper -->


  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.1.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('admin/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('admin/dist/js/adminlte.js')}}"></script>
<!-- Toastr -->
<script src="{{asset('admin/plugins/toastr/toastr.min.js')}}"></script>

<!-- TOAST START HERE -->

<script>

toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": true,
  "progressBar": true,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}

$( document ).ready(function() { 

    @if(session()->has('error'))
      toastr['error']('<?php echo session()->pull('error'); ?>')
    @elseif(session()->has('success'))
      toastr['success']('<?php echo session()->pull('success'); ?>')
    @endif
});


</script>






<!-- TOAST END HERE -->

@stack('javascript')

</body>
</html>