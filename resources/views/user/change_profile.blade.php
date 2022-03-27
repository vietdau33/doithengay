@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Thay đổi thông tin</p>
        </div>
        <div class="body-content">
            <div class="box-content p-3 mt-4">
                <form action="{{ route('profile.change.post') }}" method="POST">
                    @csrf
                    <div class="row m-0 text-left">
                        <label class="w-100" for="fullname">Họ và tên</label>
                        <input type="text" class="form-control" name="fullname" id="fullname" value="{{ old('fullname') ?? user()->fullname }}">
                    </div>
                    <div class="row m-0 text-left mt-3">
                        <label class="w-100" for="phone">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone') ?? user()->phone }}">
                    </div>
                    <div class="row m-0 text-left mt-3">
                        <label class="w-100" for="email">Email</label>
                        <input type="text" class="form-control" name="email" id="email" value="{{ old('email') ?? user()->email }}">
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
