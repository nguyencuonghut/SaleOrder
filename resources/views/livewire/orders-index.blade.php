@section('title')
    Tất cả đơn hàng
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
              <li class="breadcrumb-item"><a href="/" >Trang chủ</a></li>
              <li class="breadcrumb-item active">Tất cả đơn hàng</li>
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
                <h3 class="card-title">Tất cả đơn đặt hàng</h3>

                <div class="card-tools">
                    <div class="input-group input-group-sm">
                      <input type="text" name="table_search" class="form-control float-right" placeholder="Search" wire:model.live="search">
                    </div>
                  </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
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
                      <th wire:click.prevent="sortBy('schedule_id')" ><a role="button" href="#" style="color:#212529">Kỳ đặt hàng</a>
                        @if($sortField == 'schedule_id')
                          <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'schedule_id' ? '' : 'color:#cccccc'}} "></i>
                        @else
                          <i class="fa fa-sort" style="color:#cccccc"></i>
                        @endif
                      </th>
                      <th wire:click.prevent="sortBy('creator_id')" ><a role="button" href="#" style="color:#212529">Người tạo</a>
                        @if($sortField == 'creator_id')
                          <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'creator_id' ? '' : 'color:#cccccc'}} "></i>
                        @else
                          <i class="fa fa-sort" style="color:#cccccc"></i>
                        @endif
                      </th>
                      <th wire:click.prevent="sortBy('status')" ><a role="button" href="#" style="color:#212529">Trạng thái</a>
                        @if($sortField == 'status')
                          <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'status' ? '' : 'color:#cccccc'}} "></i>
                        @else
                          <i class="fa fa-sort" style="color:#cccccc"></i>
                        @endif
                      </th>
                      <th wire:click.prevent="sortBy('delivery_date')" ><a role="button" href="#" style="color:#212529">Ngày lấy hàng</a>
                        @if($sortField == 'delivery_date')
                          <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'delivery_date' ? '' : 'color:#cccccc'}} "></i>
                        @else
                          <i class="fa fa-sort" style="color:#cccccc"></i>
                        @endif
                      </th>
                      <th>Số lượng</th>
                      <th>Thao tác</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php $sl = 0; ?>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{++$sl}}</td>
                            <td>{{$order->schedule->title}}</td>
                            <td>{{$order->creator->name}}</td>
                            <td>
                                @if("Chưa duyệt" == $order->status)
                                    <span class="badge badge-secondary">{{$order->status}}</span>
                                @elseif ("Giám đốc đã duyệt" == $order->status)
                                    <span class="badge badge-success">{{$order->status}}</span>
                                @else
                                    <span class="badge badge-warning">{{$order->status}}</span>
                                @endif
                            </td>
                            <td>{{$order->delivery_date}}</td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}">
                                {{$order->product_cnt}} sản phẩm {{ number_format($order->total_weight, 0, '.', ',') }} KG
                                </a>
                            </td>
                            <td>
                                @if ($deletedOrderIndex === $order->id)
                                    <button type="button" wire:click.prevent="destroy" class="btn btn-outline-success btn-sm"><i class="fa fa-save"></i></button>
                                    <button type="button" wire:click.prevent="cancel" class="btn btn-outline-danger btn-sm"><i class="fa fa-times-circle"></i></button>
                                @else
                                    <a href="{{route('orders.edit', $order->id)}}"><button class="btn btn-outline-warning btn-sm"><i class="fa fa-edit"></i></button></a>
                                    <button type="button" wire:click.prevent="confirmDestroy({{$order->id}})" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
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
