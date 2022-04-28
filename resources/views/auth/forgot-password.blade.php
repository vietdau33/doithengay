@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/phone.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Quên mật khẩu</p>
        </div>
        <div class="body-content">
            <div class="box-content p-3 mt-4">
                <form action="{{ route('auth.forgot.post') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $step }}" name="step">
                    @if($step == 'start')
                        <div class="row m-0">
                            <label class="w-100 text-center" for="dasfsafsad">Email hoặc tên đăng nhập *</label>
                            <input
                                type="text"
                                class="form-control text-center"
                                name="dasfsada"
                                id="dasfsafsad"
                                value="{{ old('dasfsada') }}"
                                autocomplete="off"
                            />
                        </div>
                    @elseif($step == 'verify')
                        <input type="hidden" name="dasfsada" value="{{ $hash }}">
                        <div class="alert alert-warning">
                            Đã gửi mã xác nhận đến mail bạn đã đăng ký
                            Kiểm tra cả hộp thư rác nếu bạn không tìm thấy email trong hộp thư chính.
                        </div>
                        <div class="row m-0">
                            <label class="w-100 text-center" for="verify_code">Nhập mã xác nhận *</label>
                            <input
                                type="text"
                                class="form-control text-center"
                                name="verify_code"
                                id="verify_code"
                                autocomplete="off"
                            />
                        </div>
                    @elseif($step == 'new')
                        <input type="hidden" value="{{ $hash }}" name="hash">
                        <div class="row m-0">
                            <label class="w-100 text-center" for="new_password">Nhập mật khẩu mới *</label>
                            <input
                                type="password"
                                class="form-control text-center"
                                name="new_password"
                                id="new_password"
                                autocomplete="off"
                            />
                        </div>
                        <div class="row m-0">
                            <label class="w-100 text-center" for="new_password_2">Nhập lại mật khẩu mới *</label>
                            <input
                                type="password"
                                class="form-control text-center"
                                name="new_password_2"
                                id="new_password_2"
                                autocomplete="off"
                            />
                        </div>
                    @endif
                    <div class="row m-0 mt-3 justify-content-center ">
                        <button class="btn btn-primary">Tiếp tục</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
