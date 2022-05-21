@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Cài đặt bảo mật</p>
        </div>
        <div class="body-content">
            <div class="box-content text-left p-3 mt-4">
                <h5 class="font-weight-bold">Xác thực 2 lớp</h5>
                <div class="form-group">
                    <label class="toggle" for="security_level_2" style="cursor:pointer;">
                        <span class="d-inline-block mr-2">Trạng thái kích hoạt bảo mật 2 lớp</span>
                        <input type="checkbox" class="toggle__input"
                               id="security_level_2" {{ user()->security_level_2 === 1 ? 'checked' : '' }} />
                        <span class="toggle-track">
                            <span class="toggle-indicator">
                                <span class="checkMark">
                                    <svg viewBox="0 0 24 24" id="ghq-svg-check" role="presentation" aria-hidden="true">
                                        <path
                                            d="M9.86 18a1 1 0 01-.73-.32l-4.86-5.17a1.001 1.001 0 011.46-1.37l4.12 4.39 8.41-9.2a1 1 0 111.48 1.34l-9.14 10a1 1 0 01-.73.33h-.01z"></path>
                                    </svg>
                                </span>
                            </span>
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_otp_change_security_level_2" tabindex="-1" role="dialog"
         aria-labelledby="modal_otp_change_security_level_2" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Xác minh OTP</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="w-100 font-weight-bold" for="otp_code">OTP Code *</label>
                        <div class="d-flex w-100">
                            <input type="hidden" class="form-control" name="otp_hash" id="otp_hash"
                                   value="{{ old('otp_hash') }}">
                            <input type="text" class="form-control" name="otp_code" id="otp_code"
                                   value="{{ old('otp_code') }}" placeholder="123456">
                            <div class="btn btn-primary ml-2 send-otp-security-level-2" style="min-width: 110px;">Gửi
                                OTP
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary btn-submit-change-status">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('[for="security_level_2"]').on('click', function (e) {
            e.preventDefault();
            $("#modal_otp_change_security_level_2").modal();
        });
        $('.send-otp-security-level-2').on('click', function (e) {
            e.preventDefault();
            if ($(this).hasClass('clicked')) {
                return false;
            }
            $(this).addClass('clicked');
            $(this).text('Sending...');
            const self = this;
            Request.ajax('{{ route('security.otp_change_status') }}', function (result) {
                if (!result.success) {
                    alertify.alert('Error', result.message);
                    $('.alertify .ajs-header').addClass('alert-danger');
                    return;
                }
                $('#otp_hash').val(result.data.hash);
                $(self).removeClass('clicked').text('Gửi OTP');
                alertify.alert('Notification', result.message);
                $('.alertify .ajs-header').addClass('alert-success');
            });
        });
        $('.btn-submit-change-status').on('click', function () {
            const otp_hash = $('#otp_hash').val().trim();
            const otp_code = $('#otp_code').val().trim();
            Request.ajax('{{ route('security.change_status') }}', {otp_hash, otp_code}, function (result) {
                if (!result.success) {
                    alertify.alert('Error', result.message);
                    $('.alertify .ajs-header').addClass('alert-danger');
                    return;
                }
                alertify.alert('Notification', result.message);
                $('.alertify .ajs-header').addClass('alert-success');
                $("#modal_otp_change_security_level_2").modal('hide');
                $("#security_level_2").prop('checked', result.status)
            });
        });
    </script>
@endsection
