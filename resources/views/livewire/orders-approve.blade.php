@section('title')
    Duyệt đơn đặt hàng
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
              <li class="breadcrumb-item"><a href="{{route('orders.index')}}" >Tất cả đơn đặt hàng</a></li>
              <li class="breadcrumb-item active">Duyệt đơn</li>
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
                  <h3 class="card-title">Duyệt đơn đặt hàng</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form wire:submit.prevent="approveOrder">
                  <div class="card-body">
                    @if('Giám đốc' == Auth::user()->role->name)
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <div wire:key="UNIQUE_KEY">
                                  <div>
                                    <label class="required-field" class="control-label" for="level1_manager_approved_result">Kết quả</label>
                                    <div class="controls">
                                        <select style="width:100%;" name="level1_manager_approved_result" id="level1_manager_approved_result" class="form-control select2" wire.model.defer="level1_manager_approved_result">
                                            <option selected="selected" disabled>-- Chọn --</option>
                                            <option value="Đồng ý">Đồng ý</option>
                                            <option value="Từ chối">Từ chối</option>
                                        </select>
                                    </div>
                                    @error('level1_manager_approved_result')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if('TV/GS' == Auth::user()->role->name)
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <div wire:key="UNIQUE_KEY">
                                  <div>
                                    <label class="required-field" class="control-label" for="level2_manager_approved_result">Kết quả</label>
                                    <div class="controls">
                                        <select style="width:100%;" name="level2_manager_approved_result" id="level2_manager_approved_result" class="form-control select2" wire.model.defer="level2_manager_approved_result">
                                            <option selected="selected" disabled>-- Chọn --</option>
                                            <option value="Đồng ý">Đồng ý</option>
                                            <option value="Từ chối">Từ chối</option>
                                        </select>
                                    </div>
                                    @error('level2_manager_approved_result')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                  <div class="card-footer">
                      <button type="submit" class="btn btn-success">Duyệt</button>
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
    $('#level1_manager_approved_result').change(function (e) {
        let elementName = $(this).attr('id');
        @this.set(elementName, e.target.value);
    });
    $('#level2_manager_approved_result').change(function (e) {
        let elementName = $(this).attr('id');
        @this.set(elementName, e.target.value);
    });
</script>
@endpush
