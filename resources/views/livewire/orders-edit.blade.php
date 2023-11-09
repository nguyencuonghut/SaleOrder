@section('title')
    Sửa đặt hàng
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
              <li class="breadcrumb-item"><a href="/" wire:navigate>Trang chủ</a></li>
              <li class="breadcrumb-item"><a href="{{route('orders.index')}}" wire:navigate>Tất cả đơn đặt hàng</a></li>
              <li class="breadcrumb-item active">Sửa đơn đặt hàng</li>
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
                <!-- form start -->
                <form wire:submit.prevent="saveOrder">
                  <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <div wire:key="UNIQUE_KEY">
                                  <div>
                                    <label class="required-field" class="control-label" for="schedule_id">Kỳ</label>
                                    <div class="controls">
                                        <select style="width:100%;" name="schedule_id" id="schedule_id" class="form-control select2" wire.model.defer="schedule_id">
                                            <option selected="selected" disabled>-- Chọn --</option>
                                            @foreach ($schedules as $schedule)
                                                <option {{$order->schedule->id == $schedule->id ? 'selected' : ''}} value="{{$schedule->id}}">{{$schedule->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('schedule_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label class="control-label" for="delivery_date">Ngày lấy hàng </label>
                            <input type="text" class="form-control" id="delivery_date" name="delivery_date" wire:model.lazy="delivery_date" autocomplete="off">
                            @error('delivery_date') <span style="color:red;">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                  <div class="card-footer">
                      <button type="submit" class="btn btn-success">Lưu</button>
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
    $('#schedule_id').change(function (e) {
        let elementName = $(this).attr('id');
        @this.set(elementName, e.target.value);
    });
    $('#level2_manager_id').change(function (e) {
        let elementName = $(this).attr('id');
        @this.set(elementName, e.target.value);
    });
    $('#level1_manager_id').change(function (e) {
        let elementName = $(this).attr('id');
        @this.set(elementName, e.target.value);
    });
    var picker = new Pikaday({
        field: document.getElementById('delivery_date'),
        onSelect: function() {
            @this.set('delivery_date', this.getMoment().format('DD/MM/YYYY'));
        },
        toString(date, format) {
            const day = date.getDate();
            const month = date.getMonth() + 1;
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        },
    });
</script>
@endpush
