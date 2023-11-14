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
              <li class="breadcrumb-item active">Giỏ hàng</li>
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

            @if(Cart::getContent()->count() > 0)
            <!-- Order table -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Giỏ hàng</h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm">
                        <button type="submit" class="btn btn-danger btn-sm" wire:click.prevent="destroyAll()">Xóa tất cả</button>
                    </div>
                  </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>STT</th>
                      <th>Mã SP</th>
                      <th>Trọng lượng</th>
                      <th>Thao tác</th>
                    </tr>
                  </thead>
                  <tbody>
                      @php
                          $i = 0;
                      @endphp
                    @foreach (Cart::getContent()->sortBy('id') as $item)
                        @php
                            $i++;
                        @endphp
                        <tr>
                            <td>{{$i}}</td>
                            @php
                                $product = App\Models\Product::findOrFail($item->id);
                            @endphp
                            <td>{{$item->name}} {{$product->detail}}</td>
                            <td>
                                @if ($editedRowId === $item->id)
                                    <input type="number" class="form-control" wire:model.defer="quantity">
                                @error('quantity') <span style="color:red;">{{ $message }}</span>@enderror
                                @else
                                    {{number_format($item->quantity , 0, '.', ',')}} KG
                                @endif
                            </td>
                            <td>
                                @if ($editedRowId === $item->id)
                                    <button type="button" wire:click.prevent="update" class="btn btn-outline-success btn-sm"><i class="fa fa-save"></i></button>
                                    <button type="button" wire:click.prevent="cancel" class="btn btn-outline-danger btn-sm"><i class="fa fa-times-circle"></i></button>
                                @elseif ($destroyedRowId === $item->id)
                                    <button type="button" wire:click.prevent="destroy" class="btn btn-outline-success btn-sm"><i class="fa fa-check-square"></i></button>
                                    <button type="button" wire:click.prevent="cancel" class="btn btn-outline-danger btn-sm"><i class="fa fa-times-circle"></i></button>
                                @else
                                    <button type="button" wire:click.prevent="edit({{$item->id}})" class="btn btn-outline-warning btn-sm"><i class="fa fa-edit"></i></button>
                                    <button type="button" wire:click.prevent="confirmDestroy({{$item->id}})" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                        <tr style="color:#C82333;">
                            <td colspan=2>Tổng</td>
                            <td colspan=2>{{ number_format(Cart::getTotalQuantity(), 0, '.', ',') }} KG</td>
                        </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              @can('create-order')
              <div class="card-footer">
                <a href="{{route('orders.create')}}" style="width: 100%;" class="btn btn-success btn-sm">Đặt hàng</a>
              </div>
              @endcan
            </div>
            <!-- /.card -->
            @else
            <h3>Giỏ hàng trống!</h3>
            <a href="{{route('home')}}" class="btn btn-success btn-sm">Tạo đơn hàng</a>

            @endif
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
