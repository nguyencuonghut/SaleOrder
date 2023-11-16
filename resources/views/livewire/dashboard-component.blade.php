@section('title')
    Dashboard
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
              <li class="breadcrumb-item active">Dashboard</li>
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
                <h3 class="card-title">Dashboard</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <div class="row" style="margin:10px;">
                    <div class="col-4">
                        <div class="form-group">
                            <div wire:key="UNIQUE_KEY">
                              <div>
                                <label class="control-label" for="schedule_id">Lọc theo kỳ</label>
                                <div class="controls">
                                    <select name="schedule_id" id="schedule_id" class="form-control select2" wire.model.defer="schedule_id">
                                        @foreach ($schedules as $schedule)
                                            <option value="{{$schedule->id}}">{{$schedule->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('schedule_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                              </div>
                            </div>
                        </div>
                    </div>

                    @if('Admin' == Auth::user()->role->name
                    || 'Sản Xuất' == Auth::user()->role->name)
                    <div class="col-4">
                        <div class="form-group">
                            <div wire:key="UNIQUE_KEY">
                              <div>
                                <label class="control-label" for="level1_manager_id">Lọc theo GĐ</label>
                                <div class="controls">
                                    <select name="level1_manager_id" id="level1_manager_id" class="form-control select2" wire.model.defer="level1_manager_id">
                                        <option selected value="All">Tất cả</option>
                                        @foreach ($level1_managers as $level1_manager)
                                            <option value="{{$level1_manager->id}}">{{$level1_manager->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('level1_manager_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                              </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-4">
                        <label class="control-label">Export dữ liệu</label>
                        <br>
                        <button type="button" class="btn btn-success" wire:click="exportExcel"><i class="fas fa-cloud-download-alt"> Tải</i></button>
                    </div>
                </div>

              </div>
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Mã</th>
                      <th>Trọng lượng (KG)</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php $sl = 0; ?>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{++$sl}}</td>
                            <td>{{$product->code}} {{$product->detail}}</td>
                            <td>{{number_format($product->quantity , 0, '.', ',')}}</td>
                        </tr>
                    @endforeach
                    <tr style="font-weight: bold;">
                        <td colspan=2>Tổng</td>
                        <td colspan=2>{{ number_format($products->sum('quantity'), 0, '.', ',') }}</td>
                    </tr>
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


@push('scripts')
<!-- Select2 -->
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    $('#schedule_id').change(function (e) {
        let elementName = $(this).attr('id');
        @this.set(elementName, e.target.value);
    });
    $('#level1_manager_id').change(function (e) {
        let elementName = $(this).attr('id');
        @this.set(elementName, e.target.value);
    });
</script>
@endpush
