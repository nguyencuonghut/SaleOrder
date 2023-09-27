@section('title')
    Người dùng
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
              <li class="breadcrumb-item active">Người dùng</li>
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
                <h3 class="card-title">Tất cả người dùng</h3>

                <div class="card-tools">
                  <div class="input-group input-group-sm">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search" wire:model.live="search">
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <button style="margin: 10px;" type="button" wire:click.prevent="addNew" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button>
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Tên</th>
                      <th>Email</th>
                      <th>Vai trò</th>
                      <th>Thao tác</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($users as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td><a href="#">{{$item->name}}</a></td>
                            <td>{{$item->email}}</td>
                            <td>{{$item->role->name}}</td>
                            <td>
                                <button type="button" class="btn btn-outline-success btn-sm"><i class="fa fa-eye"></i></button>
                                <button type="button" class="btn btn-outline-warning btn-sm"><i class="fa fa-edit"></i></button>
                                <button type="button" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                  </tbody>
                </table>
                @if (count($users))
                    <p style="margin: 10px;">Showing {{$users->count()}} of {{$users->total()}} entries</p>
                    {{ $users->links('livewire.pagination-links') }}
                @endif
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

  @include('livewire.user-create')
</div>

@push('scripts')

<script>
    $(document).ready(function () {
        window.addEventListener('hide-form', function (event) {
            $('#form').modal('hide');
        });
        });
        window.addEventListener('show-form', function (event) {
            $('#form').modal('show');
        });
</script>
@endpush
