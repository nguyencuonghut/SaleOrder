@section('title')
    Sửa kỳ đặt hàng
@endsection

@push('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<!-- PikaDay -->
<link rel="stylesheet" href="{{asset('plugins/pikaday/pikaday.css')}}">
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
              <li class="breadcrumb-item"><a href="{{route('schedules.index')}}" >Kỳ đặt hàng</a></li>
              <li class="breadcrumb-item active">Sửa</li>
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
                  <h3 class="card-title">Sửa kỳ đặt hàng</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form wire:submit.prevent="saveSchedule">
                  <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label class="required-field" for="title">Tiêu đề</label>
                            <input type="text" class="form-control" id="title" name="title" wire:model="title" placeholder="Đặt hàng cho kỳ ? tháng ? năm ?">
                            @error('title')
                              <span class="text-danger"> {{ $message }}</span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <div wire:key="UNIQUE_KEY">
                                  <div wire:ignore>
                                    <label class="required-field" class="control-label" for="period">Kỳ</label>
                                    <div class="controls">
                                        <select style="width:100%;" name="period" id="period" class="form-control select2" wire.model.defer="period">
                                            <option selected="selected" disabled>-- Chọn --</option>
                                            <option {{"Kỳ 1" == $period ? 'selected' : ''}} value="Kỳ 1">Kỳ 1</option>
                                            <option {{"Kỳ 2" == $period ? 'selected' : ''}} value="Kỳ 2">Kỳ 2</option>
                                            <option {{"Kỳ 3" == $period ? 'selected' : ''}} value="Kỳ 3">Kỳ 3</option>
                                            <option {{"Kỳ 4" == $period ? 'selected' : ''}} value="Kỳ 4">Kỳ 4</option>
                                        </select>
                                    </div>
                                    @error('period')
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
                        <div class="col-6">
                            <label class="required-field" class="control-label">Thời gian bắt đầu</label>
                            <input type="text" class="form-control" id="start_time" name="start_time" wire:model.lazy="start_time" autocomplete="off">
                            @error('start_time') <span style="color:red;">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-6">
                            <label class="required-field" class="control-label">Thời gian kết thúc</label>
                            <input type="text" class="form-control" id="end_time" name="end_time" wire:model.lazy="end_time" autocomplete="off">
                            @error('end_time') <span style="color:red;">{{ $message }}</span>@enderror
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
<!-- Moment JS -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- PikaDay -->
<script src="{{asset('plugins/pikaday/pikaday.js')}}"></script>
<script>
    $('#period').change(function (e) {
        let elementName = $(this).attr('id');
        @this.set(elementName, e.target.value);
    });
    var picker = new Pikaday({
        field: document.getElementById('start_time'),
        onSelect: function() {
            @this.set('start_time', this.getMoment().format('DD/MM/YYYY'));
        },
        toString(date, format) {
            const day = date.getDate();
            const month = date.getMonth() + 1;
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        },
    });
    var picker = new Pikaday({
        field: document.getElementById('end_time'),
        onSelect: function() {
            @this.set('end_time', this.getMoment().format('DD/MM/YYYY'));
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
