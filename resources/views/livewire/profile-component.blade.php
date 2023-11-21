@section('title')
    'Hồ sơ của tôi'
@endsection

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Hồ sơ của tôi</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
              <li class="breadcrumb-item active">Hồ sơ của tôi</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
            <div class="col-md-12">
                <!-- Display session message -->
                @if(Session::has('success_message'))
                    <div class="alert alert-success">
                        {{ Session::get('success_message') }}
                    </div>
                @endif
                @if(Session::has('error_message'))
                    <div class="alert alert-danger">
                        {{ Session::get('error_message') }}
                    </div>
                @endif

                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">

                        <h3 class="profile-username text-center">{{Auth::user()->name}}</h3>

                        <p class="text-muted text-center">{{Auth::user()->email}}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            @if(Auth::user()->role->name == 'Nhân viên')
                            <li class="list-group-item">
                                <b>Số đơn tôi tạo</b> <a class="float-right">{{$my_orders_cnt}}</a>
                            </li>
                            @endif
                            @if(Auth::user()->role->name == 'Giám đốc')
                            <li class="list-group-item">
                                <b>Số đơn tôi tạo</b> <a class="float-right">{{$my_orders_cnt}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Số đơn tôi duyệt</b> <a class="float-right">{{$my_level1_approved_orders_cnt}}</a>
                            </li>
                            @endif
                            @if(Auth::user()->role->name == 'TV/GS')
                            <li class="list-group-item">
                                <b>Số đơn tôi tạo</b> <a class="float-right">{{$my_orders_cnt}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Số đơn tôi duyệt</b> <a class="float-right">{{$my_level2_approved_orders_cnt}}</a>
                            </li>
                            @endif
                        </ul>

                        <a href="{{route('profile.password.change')}}" class="btn btn-warning btn-block"><b>Đổi mật khẩu</b></a>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.row -->
            </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
