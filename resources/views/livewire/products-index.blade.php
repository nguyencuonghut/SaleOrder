@section('title')
    Sản phẩm
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
              <li class="breadcrumb-item active">Sản phẩm</li>
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
                <h3 class="card-title">Tất cả sản phẩm</h3>

                <div class="card-tools">
                    <div class="input-group input-group-sm">
                      <input type="text" name="table_search" class="form-control float-right" placeholder="Search" wire:model.live="search">
                    </div>
                  </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <a style="margin: 10px;" href="{{route('products.create')}}" ><button class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button></a>
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
                      <th wire:click.prevent="sortBy('code')" ><a role="button" href="#" style="color:#212529">Mã SP</a>
                        @if($sortField == 'code')
                          <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'code' ? '' : 'color:#cccccc'}} "></i>
                        @else
                          <i class="fa fa-sort" style="color:#cccccc"></i>
                        @endif
                      </th>
                      <th wire:click.prevent="sortBy('package_id')" ><a role="button" href="#" style="color:#212529">Đóng gói</a>
                        @if($sortField == 'package_id')
                          <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'package_id' ? '' : 'color:#cccccc'}} "></i>
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
                      <th wire:click.prevent="sortBy('category_id')" ><a role="button" href="#" style="color:#212529">Phân loại</a>
                        @if($sortField == 'category_id')
                          <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'category_id' ? '' : 'color:#cccccc'}} "></i>
                        @else
                          <i class="fa fa-sort" style="color:#cccccc"></i>
                        @endif
                      </th>
                      <th wire:click.prevent="sortBy('detail')" ><a role="button" href="#" style="color:#212529">Chi tiết</a>
                        @if($sortField == 'detail')
                          <i class="fa {{ $sortAsc == true ? 'fa-sort-up' : 'fa-sort-down' }}" style=" {{ $sortField == 'detail' ? '' : 'color:#cccccc'}} "></i>
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
                      <th>Thao tác</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php $sl = 0; ?>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{++$sl}}</td>
                            <td>
                                @if ($editProductIndex === $product->id)
                                    <input type="text" class="form-control" wire:model.defer="code">
                                    @error('code') <span style="color:red;">{{ $message }}</span>@enderror
                                @else
                                    {{$product->code}}
                                @endif
                            </td>
                            <td>
                                @if ($editProductIndex === $product->id)
                                    <select style="width:100%;" class="form-control" wire:model="editPackageId">
                                        @foreach($packages as $package)
                                            <option value="{{$package->id}}">{{$package->name}}</option>
                                        @endforeach
                                    </select>
                                @else
                                    {{$product->package->name}}
                                @endif
                            </td>
                            <td>
                                @if ($editProductIndex === $product->id)
                                    <select style="width:100%;" class="form-control" wire:model="editGroupId">
                                        @foreach($groups as $group)
                                            <option value="{{$group->id}}">{{$group->name}}</option>
                                        @endforeach
                                    </select>
                                @else
                                    {{$product->group->name}}
                                @endif
                            </td>
                            <td>
                                @if ($editProductIndex === $product->id)
                                    <select style="width:100%;" class="form-control" wire:model="editCategoryId">
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                @else
                                    {{$product->category->name}}
                                @endif
                            </td>
                            <td>
                                @if ($editProductIndex === $product->id)
                                    <input type="text" class="form-control" wire:model.defer="detail">
                                    @error('detail') <span style="color:red;">{{ $message }}</span>@enderror
                                @else
                                    {{$product->detail}}
                                @endif
                            </td>
                            <td>
                                @if ($editProductIndex === $product->id)
                                    <select style="width:100%;" class="form-control" wire:model="editProductStatus">
                                        <option value="Kích hoạt">Kích hoạt</option>
                                        <option value="Vô hiệu">Vô hiệu</option>
                                    </select>
                                @else
                                    <span class="badge {{'Vô hiệu' == $product->status ? 'badge-danger' : 'badge-success'}}">{{$product->status}}</span>

                                @endif
                            </td>
                            <td>
                                @if ($editProductIndex === $product->id)
                                    <button type="button" wire:click.prevent="save" class="btn btn-outline-success btn-sm"><i class="fa fa-save"></i></button>
                                    <button type="button" wire:click.prevent="cancel" class="btn btn-outline-danger btn-sm"><i class="fa fa-times-circle"></i></button>
                                @elseif ($deletedProductIndex === $product->id)
                                    <button type="button" wire:click.prevent="destroy" class="btn btn-outline-success btn-sm"><i class="fa fa-check-square"></i></button>
                                    <button type="button" wire:click.prevent="cancel" class="btn btn-outline-danger btn-sm"><i class="fa fa-times-circle"></i></button>
                                @else
                                    <button type="button" wire:click.prevent="edit({{$product->id}})" class="btn btn-outline-warning btn-sm"><i class="fa fa-edit"></i></button>
                                    <button type="button" wire:click.prevent="confirmDestroy({{$product->id}})" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                  </tbody>
                </table>
                @if (count($products))
                    <p style="margin: 10px;">Showing {{$products->count()}} of {{$products->total()}} entries</p>
                    {{ $products->links('livewire.pagination-links') }}
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
</div>
