@push('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
@endpush
  <!-- Modal -->
  <div class="modal fade" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <form autocomplete="off" wire:submit="addUser">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <span>Thêm người dùng</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="required-field" for="name">Tên</label>
                        <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" id="name" aria-describedby="nameHelp">
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="required-field" for="email">Email</label>
                        <input type="text" wire:model="email" class="form-control @error('email') is-invalid @enderror" id="email" aria-describedby="emailHelp">
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="required-field" for="password">Mật khẩu</label>
                        <input type="password" wire:model="password" class="form-control @error('password') is-invalid @enderror" id="password">
                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="required-field" for="passwordConfirmation">Xác nhận mật khẩu</label>
                        <input type="password" wire:model="password_confirmation" class="form-control" id="passwordConfirmation">
                    </div>

                    <div class="form-group">
                        <div wire:key="UNIQUE_KEY">
                          <div wire:ignore>
                            <label class="required-field" class="control-label" for="role_id">Vai trò</label>
                            <div class="controls">
                                <select style="width:100%;" name="role_id" id="role_id" class="form-control select2" wire.model.defer="role_id">
                                    <option selected="selected" disabled>-- Chọn --</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('role_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-times mr-1"></i> Hủy</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save mr-1"></i>
                        <span>Lưu</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


@push('scripts')
<!-- Select2 -->
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>

<script>
    $('#role_id').change(function (e) {
        let elementName = $(this).attr('id');
        @this.set(elementName, e.target.value);
    });
</script>
@endpush
