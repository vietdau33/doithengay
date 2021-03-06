@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/phone.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">ĐĂNG NHẬP</p>
        </div>
        <div class="body-content">
            <div class="box-content p-3 mt-4">
                <form action="{{ route('auth.post') }}" method="POST">
                    @csrf
                    <div class="row m-0">
                        <label class="w-100 text-center" for="username">Tên đăng nhập *</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="Tên đăng nhập" value="{{ old('username') }}">
                    </div>
                    <div class="row m-0 mt-3">
                        <label class="w-100 text-center" for="password">Mật khẩu *</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Mật khẩu">
                    </div>
                    <div class="row m-0 mt-3 justify-content-center ">
                        <button class="btn btn-primary">ĐĂNG NHẬP</button>
                    </div>
                </form>
                <hr>
                <div class="row pl-3 pr-3 justify-content-between">
                    <a href="{{ route('auth.forgot.view') }}">Quên mật khẩu?</a>
                    <a href="{{ route('auth.register.view') }}">Đăng ký?</a>
                </div>
            </div>
        </div>
    </div>
@endsection
