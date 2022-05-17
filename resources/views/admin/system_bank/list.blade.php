@extends('admin_layout')
@section('contents')
    <div class="content">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Quản lý danh sách ngân hàng nhận tiền từ user</h3>
                <button class="btn btn-primary" data-toggle="modal" data-target="#modal_add_new_banks">Thêm mới</button>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-striped table-vcenter text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ngân hàng</th>
                                <th>Thông tin tài khoản</th>
                                <th>Nội dung</th>
                                <th>Nạp tối thiểu</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($stt = 1)
                            @foreach($banks as $bank)
                                <tr>
                                    <td>{{ $stt++ }}</td>
                                    <td>{{ $bank->bank_type }}</td>
                                    <td>{!! nl2br(htmlspecialchars($bank->bank_info)) !!}</td>
                                    <td>{{ $bank->bank_content }}</td>
                                    <td>{{ $bank->bank_min }}</td>
                                    <td style="width: 150px">
                                        <a href="{{ route('admin.system-bank.delete', ['id' => $bank->id]) }}" class="btn btn-danger" onclick="return confirm('Chắc chắn muốn xóa?')">Xóa</a>
                                    </td>
                                </tr>
                            @endforeach
                            @if($banks->count() <= 0)
                                <tr>
                                    <td colspan="6">Không có thẻ ngân hàng nào!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_add_new_banks" tabindex="-1" role="dialog" aria-labelledby="modal_add_new_banks" aria-hidden="true">
        <div class="modal-dialog modal-dialog-fromright" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Thêm một thẻ ngân hàng mới</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <form action="#" id="form_add_banks">
                            <div class="form-group">
                                <label for="bank_type">Ngân hàng</label>
                                <input type="text" class="form-control" name="bank_type" id="bank_type" placeholder="Tên ngân hàng">
                            </div>
                            <div class="form-group">
                                <label for="bank_info">Thông tin tài khoản</label>
                                <textarea type="text" class="form-control" id="bank_info" name="bank_info"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="bank_content" class="w-100 mb-0">Nội dung chuyển khoản</label>
                                <span class="font-italic">* Phần trong ngoặc nhọn phải là một thuộc tính có trong bảng Users</span>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="bank_content"
                                    id="bank_content"
                                    placeholder="Nội dung..."
                                    value="naptien_{id}"
                                />
                            </div>
                            <div class="form-group">
                                <label for="bank_min">Nạp tối thiểu</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="bank_min"
                                    id="bank_min"
                                    value="20.000đ"
                                    onfocus="this.select()"
                                />
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-alt-success btn-submit-add">Thêm</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        const $textarea = $("#bank_info");
        autosize($textarea);
        $('#modal_add_new_banks').on('hide.bs.modal', function () {
            $textarea.val('');
            autosize.update($textarea)
        });
        $(".btn-submit-add").on('click', function (e) {
            e.preventDefault();
            const formData = new FormData($('#form_add_banks')[0]);
            Request.ajax('{{ route('admin.system-bank.add') }}', formData, function (result) {
                alert(result.message);
                if (result.status) {
                    window.location.reload();
                }
            })
        });
    </script>
@endsection
