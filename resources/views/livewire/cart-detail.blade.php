@section('title')
    Giỏ hàng
@endsection

<div>

  <!-- Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <form autocomplete="off" wire:submit="update">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <span>Sửa số lượng</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click.prevent="cancel" >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="number" wire:model="quantity" class="form-control @error('quantity') is-invalid @enderror" id="quantity" aria-describedby="nameHelp">
                            @error('quantity')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" wire:click.prevent="cancel" ><i class="fa fa-times mr-1"></i> Hủy</button>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save mr-1"></i>
                            <span>Lưu</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-left">
              <li class="breadcrumb-item"><a href="/" wire:navigate>Trang chủ</a></li>
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
                        <button type="submit" class="btn btn-warning btn-sm" wire:click.prevent="destroyAll()">Xóa tất cả</button>
                    </div>
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
                    @foreach (Cart::getContent() as $item)
                        @php
                            $i++;
                        @endphp
                        <tr>
                            <td>{{$i}}</td>
                            @php
                                $product = App\Models\Product::findOrFail($item->id);
                            @endphp
                            <td>{{$item->name}} {{$product->detail}}</td>
                            <td>{{number_format($item->quantity , 0, '.', ',')}} KG</td>
                            <td>
                                <button data-toggle="modal" wire:click="edit({{$item->id}})" class="btn btn-warning btn-sm">Sửa</button>
                                <button class="btn btn-danger btn-sm" wire:click.prevent="destroy('{{ $item->id }}')">Xóa</button>
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
              <div class="card-footer">
                <a href="{{route('orders.create')}}" style="width: 100%;" class="btn btn-success btn-sm">Đặt hàng</a>
              </div>
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
