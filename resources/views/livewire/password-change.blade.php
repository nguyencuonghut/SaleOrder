@section('title')
    Đổi mật khẩu
@endsection

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Đổi mật khẩu</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('profile') }}">Hồ sơ</a></li>
              <li class="breadcrumb-item active">Đổi mật khẩu</li>
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
                <div class="col-12">
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
                    <div class="card">
                    <form wire:submit.prevent="changePassword">
                        {{ csrf_field() }}
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="required-field control-label">Mật khẩu</label>
                                        <div class="controls">
                                            <input type="password" class="form-control" name="password" id="password" required="" wire:model="password">
                                        </div>
                                    </div>
                                    @error('password') <span style="color:red;">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-6">
                                    <div class="control-group">
                                        <label class="required-field control-label">Xác nhận mật khẩu</label>
                                        <div class="controls">
                                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required="" wire:model="password_confirmation">
                                        </div>
                                    </div>
                                    @error('password_confirmation') <span style="color:red;">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <br>
                            <div class="control-group">
                                <div class="controls">
                                    <input type="submit" value="Cập nhật" class="btn btn-success">
                                </div>
                            </div>
                        <div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
