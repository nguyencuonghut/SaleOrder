@section('title')
    Vai trò
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

                <div class="card-tools">
                    <div class="input-group input-group-sm">
                      <input type="text" name="table_search" class="form-control float-right" placeholder="Search" wire:model.live="search">
                    </div>
                  </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <a style="margin: 10px;" href="{{route('roles.create')}}" wire:navigate><button class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button></a>
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th wire:click.prevent="sortBy('id')"><a role="button" href="#" style="color:#212529">ID</a>
                        @if($sortField == 'id')
                          <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'id' ? '' : 'color:#cccccc'}} "></i>
                        @else
                          <i class="fa fa-sort" style="color:#cccccc"></i>
                        @endif
                      </th>
                      <th wire:click.prevent="sortBy('name')" ><a role="button" href="#" style="color:#212529">Tên</a>
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
                    <?php $sl = 0; ?>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{++$sl}}</td>
                            <td>
                                @if ($editRoleIndex === $role->id)
                                    <input type="text" class="form-control" wire:model.defer="name">
                                    @error('name') <span style="color:red;">{{ $message }}</span>@enderror

                                @else
                                    {{$role->name}}
                                @endif
                            </td>
                            <td>
                                @if ($editRoleIndex === $role->id)
                                    <button type="button" wire:click.prevent="save" class="btn btn-outline-success btn-sm"><i class="fa fa-save"></i></button>
                                    <button type="button" wire:click.prevent="cancel" class="btn btn-outline-danger btn-sm"><i class="fa fa-times-circle"></i></button>
                                @elseif ($deletedRoleIndex === $role->id)
                                    <button type="button" wire:click.prevent="destroy" class="btn btn-outline-success btn-sm"><i class="fa fa-check-square"></i></button>
                                    <button type="button" wire:click.prevent="cancel" class="btn btn-outline-danger btn-sm"><i class="fa fa-times-circle"></i></button>
                                @else
                                    <button type="button" wire:click.prevent="edit({{$role->id}})" class="btn btn-outline-warning btn-sm"><i class="fa fa-edit"></i></button>
                                    <button type="button" wire:click.prevent="confirmDestroy({{$role->id}})" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
                                @endif
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
