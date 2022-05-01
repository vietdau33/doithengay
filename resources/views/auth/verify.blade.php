@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Xác minh tài khoản</p>
        </div>
        <div class="body-content">
            <div class="box-content p-3 mt-4">
                <div class="alert alert-warning">
                    <span>Nếu không thấy email ở hộp thư chính, hãy thử kiểm tra hộp thư Spam!</span>
                </div>
                <form action="{{ route('auth.verify.post') }}" method="POST">
                    @csrf
                    <div class="row m-0">
                        <label class="w-100 text-left" for="verify_code">Mã xác minh</label>
                        <input type="text" class="form-control" name="verify_code" id="verify_code" placeholder="123456">
                    </div>
                    <div class="row m-0 mt-3 justify-content-center ">
                        <button class="btn btn-primary">Xác nhận</button>
                        <button class="btn btn-secondary ml-2 btn-resend-otp">Gửi lại mã</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        let elmLoading = '<div class="lds-dual-ring"></div>';
        $('.btn-resend-otp').on('click', function (e) {
            e.preventDefault();
            if($(this).hasClass('clicked')){
                return false;
            }
            $(this).addClass('clicked').empty().append(elmLoading);
            Request.ajax('/re-send-otp', function(response){
                alert(response.message);
                $('.btn-resend-otp').remove();
            })
        })
    </script>
@endsection
