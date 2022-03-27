@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Thông tin</p>
        </div>
        <div class="body-content">
            <div class="box-content p-3 mt-4">
                <div class="row m-0 text-left">
                    <label class="w-100" for="username">Họ và tên</label>
                    <input type="text" class="form-control" id="username" value="{{ user()->fullname }}" disabled>
                </div>
                <div class="row m-0 text-left mt-3">
                    <label class="w-100" for="username">Tên đăng nhập</label>
                    <input type="text" class="form-control" id="username" value="{{ user()->username }}" disabled>
                </div>
                <div class="row m-0 text-left mt-3">
                    <label class="w-100" for="phone">Số điện thoại</label>
                    <input type="text" class="form-control" id="phone" value="{{ user()->phone }}" disabled>
                </div>
                <div class="row m-0 text-left mt-3">
                    <label class="w-100" for="email">Email</label>
                    <input type="text" class="form-control" id="email" value="{{ user()->email }}" disabled>
                </div>
                <div class="row m-0 text-left mt-3">
                    <label class="w-100" for="money">Số dư</label>
                    <input type="text" class="form-control" id="money" value="{{ number_format(user()->money) }} VNĐ" disabled>
                </div>
                <div class="row m-0 text-left mt-3">
                    <label class="w-100" for="date_create">Ngày đăng ký</label>
                    <input type="text" class="form-control" id="date_create" value="{{ date('d/m/Y', strtotime(user()->created_at)) }}" disabled>
                </div>
                <div class="row m-0 mt-3 justify-content-center ">
                    <a class="btn btn-success" href="{{ route('profile.change') }}">Đổi thông tin</a>
                    <a class="btn btn-secondary ml-2" href="{{ route('profile.password') }}">Đổi mật khẩu</a>
                </div>
            </div>
        </div>
    </div>
@endsection
