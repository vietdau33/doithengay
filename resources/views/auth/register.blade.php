@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">ĐĂNG KÝ THÀNH VIÊN</p>
        </div>
        <div class="body-content">
            <div class="box-content p-3 mt-4">
                <form action="{{ route('auth.register.post') }}" method="POST">
                    @csrf
                    <div class="row m-0">
                        <label class="w-100 text-center" for="fullname">Họ và tên *</label>
                        <input type="text" class="form-control" name="fullname" id="fullname" value="{{ old('fullname') }}" placeholder="Nguyễn Văn A">
                    </div>
                    <div class="row m-0 mt-3">
                        <label class="w-100 text-center" for="username">Tên đăng nhập *</label>
                        <input type="text" class="form-control" name="username" id="username" value="{{ old('username') }}" placeholder="Tài khoản">
                    </div>
                    <div class="row m-0 mt-3">
                        <label class="w-100 text-center" for="password">Mật khẩu *</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Mật khẩu">
                    </div>
                    <div class="row m-0 mt-3">
                        <label class="w-100 text-center" for="password_confirmation">Nhập lại mật khẩu *</label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Nhập lại mật khẩu">
                    </div>
                    <div class="row m-0 mt-3">
                        <label class="w-100 text-center" for="email">Email *</label>
                        <input type="text" class="form-control" name="email" id="email" value="{{ old('email') }}" placeholder="abc@example.com">
                    </div>
                    <div class="row m-0 mt-3">
                        <label class="w-100 text-center" for="phone">Số điện thoại *</label>
                        <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone') }}" placeholder="0123456789">
                    </div>
                    <div class="row m-0 mt-3 justify-content-center ">
                        <button class="btn btn-primary">ĐĂNG KÝ</button>
                    </div>
                </form>
                <hr>
                <div class="row pl-3 pr-3 justify-content-between">
                    <span> Bạn đã có tài khoản? <a href="{{ route('auth.view') }}">Đăng nhập</a></span>
                </div>
            </div>
        </div>
    </div>
@endsection
