@extends('admin_layout')
@section('contents')
    <div class="content">
        <!-- Change Password -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <i class="fa fa-asterisk mr-5 text-muted"></i> Đổi mật khẩu
                </h3>
            </div>
            <div class="block-content">
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
                    <div class="row m-0 mt-3 mb-3 justify-content-center ">
                        <button class="btn btn-success">Xác nhận</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Change Password -->
    </div>
@endsection
