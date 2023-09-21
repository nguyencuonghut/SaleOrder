@section('title')
    Phòng Ban
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
              <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
              <li class="breadcrumb-item active">Vai trò</li>
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
                <h3 class="card-title">Tất cả vai trò</h3>

                <div class="card-tools" style="margin: 5px;">
                  <div class="input-group input-group-sm">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search" wire:model="search">

                    <div class="input-group-append">
                      <a href="" class="btn btn-success"><i class="fas fa-plus"></i></a>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th><a wire:click.prevent="sortBy('id')" role="button" href="#" style="color:#212529">ID</a>
                        @if($sortField == 'id')
                          <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'id' ? '' : 'color:#cccccc'}} "></i>
                        @else
                          <i class="fa fa-sort" style="color:#cccccc"></i>
                        @endif
                      </th>
                      <th><a wire:click.prevent="sortBy('name')" role="button" href="#" style="color:#212529">Tên</a>
                        @if($sortField == 'name')
                          <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'name' ? '' : 'color:#cccccc'}} "></i>
                        @else
                          <i class="fa fa-sort" style="color:#cccccc"></i>
                        @endif
                      </th>
                      <th>Thao tác</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($roles as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td><a href="">{{$item->name}}</a></td>
                            <td>
                                <a href="#"><i class="fa fa-eye"></i></a>
                                <a href="#"><i class="fa fa-edit"></i></a>
                                <a href="#" wire:click.prevent=""><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
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
