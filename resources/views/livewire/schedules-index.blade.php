@section('title')
    Kỳ đặt hàng
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
              <li class="breadcrumb-item active">Kỳ đặt hàng</li>
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
                <h3 class="card-title">Tất cả kỳ đặt hàng</h3>

                <div class="card-tools">
                    <div class="input-group input-group-sm">
                      <input type="text" name="table_search" class="form-control float-right" placeholder="Search" wire:model.live="search">
                    </div>
                  </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <a style="margin: 10px;" href="{{route('schedules.create')}}" wire:navigate><button class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button></a>
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
                      <th wire:click.prevent="sortBy('title')" ><a role="button" href="#" style="color:#212529">Tiêu đề</a>
                        @if($sortField == 'title')
                          <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'title' ? '' : 'color:#cccccc'}} "></i>
                        @else
                          <i class="fa fa-sort" style="color:#cccccc"></i>
                        @endif
                      </th>
                      <th wire:click.prevent="sortBy('period')" ><a role="button" href="#" style="color:#212529">Kỳ</a>
                        @if($sortField == 'period')
                          <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'period' ? '' : 'color:#cccccc'}} "></i>
                        @else
                          <i class="fa fa-sort" style="color:#cccccc"></i>
                        @endif
                      </th>
                      <th wire:click.prevent="sortBy('start_time')" ><a role="button" href="#" style="color:#212529">Thời gian bắt đầu</a>
                        @if($sortField == 'start_time')
                          <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'start_time' ? '' : 'color:#cccccc'}} "></i>
                        @else
                          <i class="fa fa-sort" style="color:#cccccc"></i>
                        @endif
                      </th>
                      <th wire:click.prevent="sortBy('end_time')" ><a role="button" href="#" style="color:#212529">Thời gian kết thúc</a>
                        @if($sortField == 'end_time')
                          <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'end_time' ? '' : 'color:#cccccc'}} "></i>
                        @else
                          <i class="fa fa-sort" style="color:#cccccc"></i>
                        @endif
                      </th>
                      <th>Thao tác</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php $sl = 0; ?>
                    @foreach ($schedules as $schedule)
                        <tr>
                            <td>{{++$sl}}</td>
                            <td>{{$schedule->title}}</td>
                            <td>{{$schedule->period}}</td>
                            <td>{{$schedule->start_time}}</td>
                            <td>{{$schedule->end_time}}</td>
                            <td>
                                @if ($deletedScheduleIndex === $schedule->id)
                                    <button type="button" wire:click.prevent="destroy" class="btn btn-outline-success btn-sm"><i class="fa fa-save"></i></button>
                                    <button type="button" wire:click.prevent="cancel" class="btn btn-outline-danger btn-sm"><i class="fa fa-times-circle"></i></button>
                                @else
                                    <a href="{{route('schedules.edit', $schedule->id)}}" wire:navigate><button class="btn btn-outline-warning btn-sm"><i class="fa fa-edit"></i></button></a>
                                    <button type="button" wire:click.prevent="confirmDestroy({{$schedule->id}})" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
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
