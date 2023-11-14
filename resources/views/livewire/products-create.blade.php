@section('title')
    Thêm SP
@endsection

@push('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
@endpush

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
              <li class="breadcrumb-item"><a href="{{route('products.index')}}" >Sản phẩm</a></li>
              <li class="breadcrumb-item active">Thêm mới</li>
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
                  <h3 class="card-title">Thêm sản phẩm</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form wire:submit.prevent="addProduct">
                  <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label class="required-field" for="code">Mã SP</label>
                            <input type="text" class="form-control" id="code" name="code" wire:model="code">
                            @error('code')
                              <span class="text-danger"> {{ $message }}</span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="name">Tên SP</label>
                             <input type="text" class="form-control" id="name" name="name" wire:model="name">
                          </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="detail">Chi tiết (lô,dạng viên/mảnh)</label>
                            <input type="text" class="form-control" id="detail" name="detail" wire:model="detail">
                            @error('detail')
                              <span class="text-danger"> {{ $message }}</span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="description">Mô tả</label>
                             <input type="text" class="form-control" id="description" name="description" wire:model="description">
                          </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <div wire:key="UNIQUE_KEY">
                                  <div wire:ignore>
                                    <label class="required-field" class="control-label" for="package_id">Đóng gói</label>
                                    <div class="controls">
                                        <select style="width:100%;" name="package_id" id="package_id" class="form-control select2" wire.model.defer="package_id">
                                            <option selected="selected" disabled>-- Chọn --</option>
                                            @foreach($packages as $package)
                                                <option value="{{$package->id}}">{{$package->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('package_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div><div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <div wire:key="UNIQUE_KEY">
                                  <div wire:ignore>
                                    <label class="required-field" class="control-label" for="group_id">Nhóm SP</label>
                                    <div class="controls">
                                        <select style="width:100%;" name="group_id" id="group_id" class="form-control select2" wire.model.defer="group_id">
                                            <option selected="selected" disabled>-- Chọn --</option>
                                            @foreach($groups as $group)
                                                <option value="{{$group->id}}">{{$group->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('group_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div><div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <div wire:key="UNIQUE_KEY">
                                  <div wire:ignore>
                                    <label class="required-field" class="control-label" for="category_id">Phân loại</label>
                                    <div class="controls">
                                        <select style="width:100%;" name="category_id" id="category_id" class="form-control select2" wire.model.defer="category_id">
                                            <option selected="selected" disabled>-- Chọn --</option>
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('package_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                  <div class="card-footer">
                      <button type="submit" class="btn btn-success">Thêm</button>
                  </div>
                </form>
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
    $('#package_id').change(function (e) {
        let elementName = $(this).attr('id');
        @this.set(elementName, e.target.value);
    });
    $('#group_id').change(function (e) {
        let elementName = $(this).attr('id');
        @this.set(elementName, e.target.value);
    });
    $('#category_id').change(function (e) {
        let elementName = $(this).attr('id');
        @this.set(elementName, e.target.value);
    });
</script>
@endpush
