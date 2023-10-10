@section('title')
    Thêm vai trò
@endsection

<div>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-left">
              <li class="breadcrumb-item"><a href="/" wire:navigate>Trang chủ</a></li>
              <li class="breadcrumb-item"><a href="{{route('packages.index')}}" wire:navigate>Đóng gói</a></li>
              <li class="breadcrumb-item active">Thêm mới</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

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
                <div class="card-header">
                  <h3 class="card-title">Thêm đóng gói</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form wire:submit.prevent="addPackage">
                  <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label class="required-field" for="name">Tên</label>
                             <input type="text" class="form-control" id="name" name="name" wire:model="name">
                             @error('name')
                              <span class="text-danger"> {{ $message }}</span>
                            @enderror
                          </div>
                        </div>
                      </div>
                  </div>
                  <div class="card-footer">
                      <button type="submit" class="btn btn-success">Thêm</button>
                  </div>
                </form>
            </div>


          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
</div>
