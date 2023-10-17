@section('title')
    Trang chủ
@endsection

@push('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">

<style type="text/css">
td {
    white-space: normal !important; // To consider whitespace.
  }
</style>
@endpush

<div>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

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

            <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link {{ $tab == 'customer' ? 'active' : '' }}" wire:click="setTab('customer')" href="#">Nhà phân phối</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $tab == 'farm' ? 'active' : '' }}" wire:click="setTab('farm')" href="#">Trại gia công</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $tab == 'special' ? 'active' : '' }}" wire:click="setTab('special')" href="#">Hàng đặt riêng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $tab == 'silo' ? 'active' : '' }}" wire:click="setTab('silo')" href="#">Hàng Silo</a>
                    </li>
                </ul>
                <!-- ... -->
                    <div class="tab-pane container">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <div class="controls">
                                        <select style="width:100%;" name="group_id" id="group_id" class="form-control select2" wire.model.defer="group_id">
                                            <option selected="selected">-- Tất cả SP --</option>
                                            @foreach($groups as $group)
                                                <option value="{{$group->id}}">{{$group->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="col-12">
                                    <div div class="input-group input-group-sm">
                                        <input type="text" name="search" class="form-control float-right" placeholder="Search" wire:model.live="search">
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->

                        @if($tab == 'customer')
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                        <th wire:click.prevent="sortBy('code')" ><a role="button" href="#" style="color:#212529">Mã SP</a>
                                            @if($sortField == 'code')
                                            <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'code' ? '' : 'color:#cccccc'}} "></i>
                                            @else
                                            <i class="fa fa-sort" style="color:#cccccc"></i>
                                            @endif
                                        </th>
                                        <th wire:click.prevent="sortBy('group_id')" ><a role="button" href="#" style="color:#212529">Nhóm SP</a>
                                            @if($sortField == 'group_id')
                                            <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'group_id' ? '' : 'color:#cccccc'}} "></i>
                                            @else
                                            <i class="fa fa-sort" style="color:#cccccc"></i>
                                            @endif
                                        </th>
                                        <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($customer_products as $product)
                                            <tr>
                                                <td>{{$product->code}}</td>
                                                <td>{{$product->group->name}}</td>
                                                <td>
                                                <button type="button" wire:click.prevent="addToCart({{$product->id}})" class="btn btn-danger btn-sm">Đặt hàng</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if (count($customer_products))
                                    <p style="margin: 10px;">Hiển thị {{$customer_products->count()}} trên tổng số {{$customer_products->total()}} sản phẩm</p>
                                    {{ $customer_products->links('livewire.pagination-links') }}
                                @endif
                            </div>
                        @endif

                        @if($tab == 'farm')
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                        <th wire:click.prevent="sortBy('code')" ><a role="button" href="#" style="color:#212529">Mã SP</a>
                                            @if($sortField == 'code')
                                            <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'code' ? '' : 'color:#cccccc'}} "></i>
                                            @else
                                            <i class="fa fa-sort" style="color:#cccccc"></i>
                                            @endif
                                        </th>
                                        <th wire:click.prevent="sortBy('group_id')" ><a role="button" href="#" style="color:#212529">Nhóm SP</a>
                                            @if($sortField == 'group_id')
                                            <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'group_id' ? '' : 'color:#cccccc'}} "></i>
                                            @else
                                            <i class="fa fa-sort" style="color:#cccccc"></i>
                                            @endif
                                        </th>
                                        <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($farm_products as $product)
                                            <tr>
                                                <td style="width: 50%;">
                                                    {{$product->code}}
                                                    <br>{{$product->detail}}
                                                </td>
                                                <td>{{$product->group->name}}</td>
                                                <td>
                                                <button type="button" wire:click.prevent="addToCart({{$product->id}})" class="btn btn-danger btn-sm">Đặt hàng</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if (count($farm_products))
                                    <p style="margin: 10px;">Hiển thị {{$farm_products->count()}} trên tổng số {{$farm_products->total()}} sản phẩm</p>
                                    {{ $farm_products->links('livewire.pagination-links') }}
                                @endif
                            </div>
                        @endif

                        @if($tab == 'special')
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                        <th wire:click.prevent="sortBy('code')" ><a role="button" href="#" style="color:#212529">Mã SP</a>
                                            @if($sortField == 'code')
                                            <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'code' ? '' : 'color:#cccccc'}} "></i>
                                            @else
                                            <i class="fa fa-sort" style="color:#cccccc"></i>
                                            @endif
                                        </th>
                                        <th wire:click.prevent="sortBy('group_id')" ><a role="button" href="#" style="color:#212529">Nhóm SP</a>
                                            @if($sortField == 'group_id')
                                            <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'group_id' ? '' : 'color:#cccccc'}} "></i>
                                            @else
                                            <i class="fa fa-sort" style="color:#cccccc"></i>
                                            @endif
                                        </th>
                                        <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($special_products as $product)
                                            <tr>
                                                <td style="width: 50%;">
                                                    {{$product->code}}
                                                    <br>{{$product->detail}}
                                                </td>
                                                <td>{{$product->group->name}}</td>
                                                <td>
                                                <button type="button" wire:click.prevent="addToCart({{$product->id}})" class="btn btn-danger btn-sm">Đặt hàng</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if (count($special_products))
                                    <p style="margin: 10px;">Hiển thị {{$special_products->count()}} trên tổng số {{$special_products->total()}} sản phẩm</p>
                                    {{ $special_products->links('livewire.pagination-links') }}
                                @endif
                            </div>
                        @endif

                        @if($tab == 'silo')
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                        <th wire:click.prevent="sortBy('code')" ><a role="button" href="#" style="color:#212529">Mã SP</a>
                                            @if($sortField == 'code')
                                            <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'code' ? '' : 'color:#cccccc'}} "></i>
                                            @else
                                            <i class="fa fa-sort" style="color:#cccccc"></i>
                                            @endif
                                        </th>
                                        <th wire:click.prevent="sortBy('group_id')" ><a role="button" href="#" style="color:#212529">Nhóm SP</a>
                                            @if($sortField == 'group_id')
                                            <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'group_id' ? '' : 'color:#cccccc'}} "></i>
                                            @else
                                            <i class="fa fa-sort" style="color:#cccccc"></i>
                                            @endif
                                        </th>
                                        <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($silo_products as $product)
                                            <tr>
                                                <td>
                                                    {{$product->code}}
                                                    <br>{{$product->detail}}
                                                </td>
                                                <td>{{$product->group->name}}</td>
                                                <td>
                                                <button type="button" wire:click.prevent="addToCart({{$product->id}})" class="btn btn-danger btn-sm">Đặt hàng</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if (count($silo_products))
                                    <p style="margin: 10px;">Hiển thị {{$silo_products->count()}} trên tổng số {{$silo_products->total()}} sản phẩm</p>
                                    {{ $silo_products->links('livewire.pagination-links') }}
                                @endif
                            </div>
                        @endif
                        </div>
                    </div>

                </div>

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


@push('scripts')
<!-- Select2 -->
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>

<script>
    $('#group_id').change(function (e) {
        let elementName = $(this).attr('id');
        @this.set(elementName, e.target.value);
    });
</script>
@endpush

