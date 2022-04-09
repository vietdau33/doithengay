@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Nạp tiền</p>
        </div>
        <div class="body-content">
            <div class="box-content box-recharge p-3 mt-4">
                <div class="row align-items-center mt-3">
                    <div class="col-12">
                        <p class="text-center font-weight-bold">Vui lòng chuyển khoản sang 1 trong số các tài khoản sau:</p>
                    </div>
                </div>
                <div class="row align-items-center mt-3">
                    <div class="col-3"><img src="{{ asset('image/bank/momo.png') }}" alt="MOMO" class="w-100"></div>
                    <div class="col-9 pl-4">
                        <ul class="text-left">
                            <li>Số tài khoản: <b>0123456789</b></li>
                            <li>Chủ tài khoản: <b>Phạm Văn A</b></li>
                            <li>Số tiền nạp tối thiểu: <b>20.000 VND</b></li>
                            <li>Nội dung: <b>naptien_{{ user()->username }}</b></li>
                        </ul>
                    </div>
                </div>
                <div class="row align-items-center mt-3">
                    <div class="col-3"><img src="{{ asset('image/bank/mb.png') }}" alt="MB Bank" class="w-100"></div>
                    <div class="col-9 pl-4">
                        <ul class="text-left">
                            <li>Số tài khoản: <b>0020180175009</b></li>
                            <li>Chủ tài khoản: <b>Phạm Văn A</b></li>
                            <li>Số tiền nạp tối thiểu: <b>20.000 VND</b></li>
                            <li>Nội dung: <b>naptien_{{ user()->username }}</b></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        //
    </script>
@endsection
