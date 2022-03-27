@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Thay đổi mật khẩu</p>
        </div>
        <div class="body-content">
            <div class="box-content p-3 mt-4">
                <form action="{{ route('profile.password.post') }}" method="POST">
                    @csrf
                    <div class="row m-0 text-left">
                        <label class="w-100" for="password_current">Mật khẩu hiện tại</label>
                        <input type="password" class="form-control" name="password_current" id="password_current">
                    </div>
                    <div class="row m-0 text-left mt-3">
                        <label class="w-100" for="password">Mật khẩu mới</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                    <div class="row m-0 text-left mt-3">
                        <label class="w-100" for="password_confirmation">Nhập lại mật khẩu mới</label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                    </div>
                    <div class="row m-0 mt-3 justify-content-center ">
                        <button class="btn btn-success">Xác nhận</button>
                        <a class="btn btn-secondary ml-2" href="{{ route('profile.home') }}">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
