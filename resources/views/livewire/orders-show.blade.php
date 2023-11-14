@section('title')
    Giỏ hàng
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
              <li class="breadcrumb-item"><a href="{{route('orders.index')}}" >Tất cả đơn đặt hàng</a></li>
              <li class="breadcrumb-item active">Chi tiết</li>
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
            @error('qty')
            <span class="text-danger"> {{ $message }}</span>
            @enderror

            <!-- Order table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chi tiết đơn đặt hàng</h3>
                </div>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link {{ $tab == 'detail' ? 'active' : '' }}" wire:click="setTab('detail')" href="#">Chi tiết</a>
                    </li>
                    @if($logs->count())
                    <li class="nav-item">
                        <a class="nav-link {{ $tab == 'logs' ? 'active' : '' }}" wire:click="setTab('logs')" href="#">Nhật ký</a>
                    </li>
                    @endif
                </ul>

              <!-- /.card-header -->
              <div class="card-body">
                @if($tab == 'detail')
                    <div class="card">
                        <div class="card-header">
                        <h3 class="card-title">Thông tin chung</h3>
                        @if('Giám đốc đã duyệt' != $order->status)
                        <div class="card-tools">
                            <ul class="dropdown">
                                <a data-toggle="dropdown" href="#">
                                <button type="button" class="btn btn-primary btn-sm">Thao tác</button>
                                </a>

                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                    @if(('Nhân viên' == Auth::user()->role->name && 'Chưa duyệt' == $order->status)
                                    || ('TV/GS' == Auth::user()->role->name && 'TV/GS đã duyệt' == $order->status))
                                        <a href="{{route('orders.request', $order->id)}}" class="dropdown-item">
                                            Yêu cầu duyệt
                                        </a>
                                    @endif
                                    @if(('Giám đốc' == Auth::user()->role->name && 'TV/GS đã duyệt' == $order->status)
                                        || ('TV/GS' == Auth::user()->role->name && 'Chưa duyệt' == $order->status))
                                        <a href="{{route('orders.approve', $order->id)}}" class="dropdown-item">
                                            Duyệt đơn hàng
                                        </a>
                                    @endif
                                </div>
                            </ul>
                        </div>
                        @endif
                        </div>
                        <div class="card-body">
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                <address>
                                    <strong>Kỳ đặt hàng</strong><br>
                                    {{$order->schedule->title}}
                                </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                <address>
                                    <strong>Người tạo</strong><br>
                                    {{$order->creator->name}}
                                </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                <address>
                                    <strong>Trưởng vùng/Giám sát</strong><br>
                                    @if ($order->level2_manager_id)
                                        {{$order->level2_manager->name}}
                                    @else
                                        #
                                    @endif
                                    @if("Đồng ý" == $order->level2_manager_approved_result)
                                        <span class="badge badge-success">{{$order->level2_manager_approved_result}}</span>
                                    @else
                                        <span class="badge badge-danger">{{$order->level2_manager_approved_result}}</span>
                                    @endif
                                </address>
                                </div>
                            </div>
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                <address>
                                    <strong>Giám đốc</strong><br>
                                    @if ($order->level1_manager_id)
                                        {{$order->level1_manager->name}}
                                    @else
                                        #
                                    @endif

                                    @if("Đồng ý" == $order->level1_manager_approved_result)
                                        <span class="badge badge-success">{{$order->level1_manager_approved_result}}</span>
                                    @else
                                        <span class="badge badge-danger">{{$order->level1_manager_approved_result}}</span>
                                    @endif
                                </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                <address>
                                    <strong>Trạng thái</strong><br>
                                    @if("Chưa duyệt" == $order->status)
                                        <span class="badge badge-secondary">{{$order->status}}</span>
                                    @elseif ("Giám đốc đã duyệt" == $order->status)
                                        <span class="badge badge-success">{{$order->status}}</span>
                                    @else
                                        <span class="badge badge-warning">{{$order->status}}</span>
                                    @endif
                                </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                <address>
                                    <strong>Ngày lấy hàng</strong><br>
                                    {{$order->delivery_date}}
                                </address>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                        <h3 class="card-title">Danh mục SP</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                <th>STT</th>
                                <th>Mã SP</th>
                                <th>Trọng lượng</th>
                                @if(
                                    ('Nhân viên' == Auth::user()->role->name && 'Chưa duyệt' == $order->status)
                                    || ('TV/GS' == Auth::user()->role->name && 'Giám đốc đã duyệt' != $order->status)
                                )
                                <th>Thao tác</th>
                                @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($ordersproducts as $item)
                                    @php
                                        $i++;
                                        $cnt = App\Models\OrderEditLog::where('action', 'Sửa')->where('order_product_id', $item->id)->count();
                                    @endphp
                                    <tr style="{{$cnt ? 'color:red;' : ''}}">
                                        <td>{{$i}}</td>
                                        <td>{{$item->product->code}} {{$item->product->detail}}</td>
                                        <td>
                                            @if ($editedOrderProductId === $item->id)
                                                <input type="number" class="form-control" wire:model.defer="quantity">
                                            @error('quantity') <span style="color:red;">{{ $message }}</span>@enderror
                                            @else
                                                {{number_format($item->quantity , 0, '.', ',')}} KG
                                            @endif
                                        </td>
                                        @if(
                                            ('Nhân viên' == Auth::user()->role->name && 'Chưa duyệt' == $order->status)
                                            || ('TV/GS' == Auth::user()->role->name && 'Giám đốc đã duyệt' != $order->status)
                                        )
                                        <td>
                                            @if ($editedOrderProductId === $item->id)
                                                <button type="button" wire:click.prevent="update" class="btn btn-outline-success btn-sm"><i class="fa fa-save"></i></button>
                                                <button type="button" wire:click.prevent="cancel" class="btn btn-outline-danger btn-sm"><i class="fa fa-times-circle"></i></button>
                                            @elseif ($deletedOrderProductId === $item->id)
                                                <button type="button" wire:click.prevent="destroy" class="btn btn-outline-success btn-sm"><i class="fa fa-check-square"></i></button>
                                                <button type="button" wire:click.prevent="cancel" class="btn btn-outline-danger btn-sm"><i class="fa fa-times-circle"></i></button>
                                            @else
                                                <button type="button" wire:click.prevent="edit({{$item->id}})" class="btn btn-outline-warning btn-sm"><i class="fa fa-edit"></i></button>
                                                <button type="button" wire:click.prevent="confirmDestroy({{$item->id}})" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                                    <tr style="font-weight: bold;">
                                        <td colspan=2>Tổng</td>
                                        <td colspan=2>{{ number_format($order->total_weight, 0, '.', ',') }} KG</td>
                                    </tr>
                            </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($tab == 'logs')
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="logs-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Người</th>
                                <th>Sản phẩm</th>
                                <th>Lượng cũ</th>
                                <th>Lượng mới</th>
                                <th>Loại</th>
                                <th>Thời gian</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($logs as $item)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$item->creator->name}}</td>
                                        <td>{{$item->order_product->product->code}}</td>
                                        <td>{{number_format($item->old_value , 0, '.', ',')}}</td>
                                        <td>{{number_format($item->new_value , 0, '.', ',')}}</td>
                                        <td>
                                            @if("Thêm" == $item->action)
                                                <span class="badge badge-success">{{$item->action}}</span>
                                            @elseif ("Sửa" == $item->action)
                                                <span class="badge badge-warning">{{$item->action}}</span>
                                            @else
                                                <span class="badge badge-danger">{{$item->action}}</span>
                                            @endif
                                        </td>
                                        <td>{{$item->created_at}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                @endif
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
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

@push('scripts')
<script>
    $(document).ready(function () {
        window.addEventListener('hide-form', function (event) {
            $('#updateModal').modal('hide');
        });
        });
        window.addEventListener('show-form', function (event) {
            $('#updateModal').modal('show');
        });
</script>
@endpush

