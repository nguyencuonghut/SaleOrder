<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>

  <link rel="shortcut icon" href="{{ asset('favico.png') }}" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- PikaDay -->
  <link rel="stylesheet" href="{{asset('plugins/pikaday/pikaday.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <!-- Custome style -->
  <link rel="stylesheet" href="{{asset('dist/css/styles.css')}}">
  @livewireStyles()
  @stack('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
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
      <!-- Notifications Dropdown Menu -->
      @livewire('count-cart')

      @auth
      <li class="nav-item dropdown">
        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">{{Auth::user()->name}}</a>
        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
          <li><a href="#" class="dropdown-item"><i class="fas fa-user-alt"></i> Hồ sơ</a></li>
          <li><a href="{{route('logout')}}" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
      </li>
      @else
        <a href="{{route('login')}}" class="d-block" style="margin-top: 7px;"><i class="fas fa-user-lock"></i>Đăng nhập</a>
      @endauth
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('home')}}" wire:navigate class="brand-link">
      <img src="{{asset('logo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Honghafeed</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
           <li class="nav-item menu-open">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-shopping-cart"></i>
               <p>
                 Đặt hàng
                 <i class="right fas fa-angle-left"></i>
               </p>
             </a>
             <ul class="nav nav-treeview">
               <li class="nav-item">
                 <a href="{{ route('home') }}" class="nav-link {{ Request::is('/') ? 'active' : '' }}" wire:navigate>
                   <i class="far fa-circle nav-icon"></i>
                   <p>Tạo đơn hàng</p>
                 </a>
               </li>
               <li class="nav-item">
                 <a href="{{route('cart.detail')}}" class="nav-link {{ Request::is('cart') ? 'active' : '' }}" wire:navigate>
                   <i class="far fa-circle nav-icon"></i>
                   <p>Giỏ hàng</p>
                 </a>
               </li>
             </ul>
           </li>

           @if(Auth::check() && Auth::user()->role->name == 'Admin')
           <li class="nav-header">HỆ THỐNG</li>
           <li class="nav-item">
             <a href="{{ route('products.index') }}" wire:navigate class="nav-link {{ Request::is('products*') ? 'active' : '' }}">
               <i class="nav-icon fas fa-th-list"></i>
               <p>
                Sản phẩm
               </p>
             </a>
           </li>
           <li class="nav-item">
            <a href="{{ route('schedules.index') }}" wire:navigate class="nav-link {{ Request::is('schedules*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-calendar"></i>
              <p>
                Kỳ đặt hàng
              </p>
            </a>
          </li>
           <li class="nav-item">
             <a href="{{ route('packages.index') }}" wire:navigate class="nav-link {{ Request::is('packages*') ? 'active' : '' }}">
               <i class="nav-icon fas fa-box-open"></i>
               <p>
                 Đóng gói
               </p>
             </a>
           </li>
           <li class="nav-item">
             <a href="{{ route('categories.index') }}" wire:navigate class="nav-link {{ Request::is('categories*') ? 'active' : '' }}">
               <i class="nav-icon fas fa-tags"></i>
               <p>
                 Phân loại
               </p>
             </a>
           </li>
           <li class="nav-item">
             <a href="{{ route('groups.index') }}" wire:navigate class="nav-link {{ Request::is('groups*') ? 'active' : '' }}">
               <i class="nav-icon fas fa-stream"></i>
               <p>
                 Nhóm SP
               </p>
             </a>
           </li>
           <li class="nav-item">
             <a href="{{ route('roles.index') }}" wire:navigate class="nav-link {{ Request::is('roles*') ? 'active' : '' }}">
               <i class="nav-icon fas fa-book"></i>
               <p>
                 Vai trò
               </p>
             </a>
           </li>
           <li class="nav-item">
             <a href="{{ route('users.index') }}" wire:navigate class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
               <i class="nav-icon fas fa-users"></i>
               <p>
                 Người dùng
               </p>
             </a>
           </li>
           @endif
        </ul>
      </nav>
    </div>
  </aside>

  {{ $slot }}

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2021 <a href="https://honghafeed.com.vn">Nguyễn Văn Cường</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Moment JS -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- PikaDay -->
<script src="{{asset('plugins/pikaday/pikaday.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
<!-- Page specific script -->

@livewireScripts()

<style type="text/css">
    .required-field::after {
        content: " *";
        color: red;
    }
</style>

@stack('scripts')
</body>
</html>
