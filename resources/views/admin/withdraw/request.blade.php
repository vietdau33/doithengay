@extends('admin_layout')
@section('contents')
    <div class="content">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Danh sách yêu cầu rút tiền</h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-striped table-vcenter text-center">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Tài khoản</th>
                            <th>Số tiền</th>
                            <th>Ghi chú</th>
                            <th>Thanh toán</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($stt = 1)
                        @foreach($lists as $list)
                            <tr>
                                <td>{{ $stt++ }}</td>
                                <td class="font-w600">{{ $list->user->username ?? '' }}</td>
                                <td>{{ number_format($list->money) }}</td>
                                <td style="max-width: 250px">{{ $list->note }}</td>
                                <td style="max-width: 100px">
                                    <a href="#" onclick="return WithdrawRequest.getBankInfo({{ $list->bank }})">Bấm để xem thông tin thanh toán</a>
                                </td>
                                <td style="max-width: 100px">
                                    @if($list->status === 0)
                                        <a onclick="return confirm('Chắn chắn muốn thay đổi status?')"
                                           href="{{ route('admin.withdraw-request.status', ['id' => $list->id, 'status' => 1]) }}"
                                           class="btn btn-primary">Xác nhận</a>
                                        <a onclick="return confirm('Chắn chắn muốn thay đổi status?')"
                                           href="{{ route('admin.withdraw-request.status', ['id' => $list->id, 'status' => 3]) }}"
                                           class="btn btn-danger mt-1">Từ chối</a>
                                    @else
                                        <a onclick="return confirm('Chắn chắn muốn thay đổi status?')"
                                           href="{{ route('admin.withdraw-request.status', ['id' => $list->id, 'status' => 2]) }}"
                                           class="btn btn-success">Đã thanh toán</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($lists->count() <= 0)
                            <tr>
                                <td colspan="6">Không có yêu cầu nào</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_info_payment" tabindex="-1" role="dialog"
         aria-labelledby="modal_info_payment_label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header pt-2 pb-2">
                    <h3 class="modal-title" id="modal_info_payment_label">Thông tin thanh toán</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 16px 22px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 20px">
                    <div class="row">
                        <div class="col-6 text-right">Loại:</div>
                        <div class="col-6"><b data-el="type"></b></div>
                    </div>
                    <div class="row">
                        <div class="col-6 text-right">Tên:</div>
                        <div class="col-6"><b data-el="name"></b></div>
                    </div>
                    <div class="row">
                        <div class="col-6 text-right">STK:</div>
                        <div class="col-6"><b data-el="a_number"></b></div>
                    </div>
                    <div class="row">
                        <div class="col-6 text-right">Họ Tên:</div>
                        <div class="col-6"><b data-el="a_name"></b></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        window.WithdrawRequest = {
            getBankInfo: function (bank_id) {
                Request.ajax('{{ route('admin.bank.info') }}', {bank_id}, function (result) {
                    if (!result.success) {
                        alertify.alert('Error', result.message);
                        $('.alertify .ajs-header').addClass('alert-danger');
                        return false;
                    }
                    let modal = $('#modal_info_payment');
                    for(let key in result.datas){
                        modal.find('[data-el="' + key + '"]').text(result.datas[key])
                    }
                    modal.modal('show');
                });
                return false;
            }
        }
    </script>
@endsection
