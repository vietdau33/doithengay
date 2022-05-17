@extends('layout')
@section('contents')
    <div class="container">
        <div class="row body-content">
            <div class="col-12 col-md-6 mt-4">
                <h4 class="font-weight-bold mb-4">Phí chuyển tiền</h4>
                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <td>Phí cố định</td>
                        <td>{{ number_format((double)$fee['transfer_fee_fix']) }} VNĐ</td>
                    </tr>
                    <tr>
                        <td>Phí chuyển tiền</td>
                        <td>{{ $fee['transfer_fee'] }}%</td>
                    </tr>
                    <tr>
                        <td>Số lần chuyển tiền tối đa trong ngày</td>
                        <td>{{ $fee['transfer_turns_on_day'] == '00' ? 'Không giới hạn' : $fee['transfer_turns_on_day'] . ' lần' }}</td>
                    </tr>
                    <tr>
                        <td>Số lần chuyển tiền trong ngày còn lại của bạn</td>
                        <td>{{ $fee['transfer_turns_on_day'] == '00' ? 'Không giới hạn' : getNumberTurnTransfer($fee['transfer_turns_on_day']) . ' lần' }}</td>
                    </tr>
                    <tr>
                        <td>Số tiền chuyển tối thiểu</td>
                        <td>{{ number_format((double)$fee['transfer_money_min']) }} VNĐ</td>
                    </tr>
                    <tr>
                        <td>Số tiền chuyển tối đa cho 1 lần chuyển</td>
                        <td>{{ number_format((double)$fee['transfer_money_max']) }} VNĐ</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-12 col-md-6 mt-4">
                <form action="{{ route('transfer.post') }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn chuyển tiền?')">
                    @csrf
                    <div class="transfer-group">
                        <div class="row m-0 mt-3">
                            <label class="w-100 font-weight-bold" for="user_receive">Tài khoản nhận</label>
                            <input
                                type="text"
                                class="form-control"
                                name="user_receive"
                                id="user_receive"
                                value="{{ old('user_receive') }}"
                                placeholder="Tài khoản nhận"
                                onchange="GetFullName(this)"
                            />
                        </div>
                        <div class="row m-0 mt-3">
                            <label class="w-100 font-weight-bold" for="user_receive_name">Tên người nhận</label>
                            <input type="text" class="form-control" id="user_receive_name" readonly>
                        </div>
                        <div class="row m-0 mt-3">
                            <label class="w-100 font-weight-bold" for="money">Số tiền</label>
                            <input
                                type="text"
                                class="form-control"
                                id="money"
                                name="money"
                                value="{{ old('money') }}"
                                placeholder="đ"
                                autocomplete="off"
                            />
                        </div>
                        <div class="row m-0 mt-3">
                            <label class="w-100 font-weight-bold" for="content">Nội dung</label>
                            <textarea cols="3" rows="2" class="form-control autosize" name="content" id="content" placeholder="Nội dung">{{ old('content') }}</textarea>
                        </div>
                        @if(user()->security_level_2 === 1)
                            <div class="row m-0 mt-3">
                                <label class="w-100 font-weight-bold" for="otp_code">OTP Code *</label>
                                <div class="d-flex w-100">
                                    <input type="hidden" class="form-control" name="otp_hash" id="otp_hash" value="{{ old('otp_hash') }}">
                                    <input type="text" class="form-control" name="otp_code" id="otp_code" value="{{ old('otp_code') }}" placeholder="123456">
                                    <div class="btn btn-primary ml-2 send-otp" style="min-width: 110px;">Send OTP</div>
                                </div>
                            </div>
                        @endif
                        <div class="row m-0 mt-3 justify-content-center ">
                            <button class="btn btn-primary" name="btn-transfer">Chuyển tiền</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        window.GetFullName = function(el) {
            const username = el.value.trim();
            Request.ajax('{{ route('transfer.get-user-name') }}', { username }, function(result) {
                $("#user_receive_name").val(result.fullname);
            });
        }
        $('.send-otp').on('click', function(e){
            e.preventDefault();
            if($(this).hasClass('clicked')){
                return false;
            }
            $(this).addClass('clicked');
            $(this).text('Sending...');
            const self = this;
            Request.ajax('{{ route('security.send-otp') }}', function(result) {
                if(!result.success){
                    alertify.alert('Error', result.message);
                    $('.alertify .ajs-header').addClass('alert-danger');
                    return;
                }
                $('#otp_hash').val(result.data.hash);
                $(self).removeClass('clicked').text('Send OTP');
                alertify.alert('Notification', result.message);
                $('.alertify .ajs-header').addClass('alert-success');
            });
        });
        $('#user_receive').trigger('change');
    </script>
@endsection
