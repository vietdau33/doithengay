@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Thêm thẻ / ví điện tử</p>
        </div>
        <div class="body-content">
            <div class="box-content box-pay-card p-3 mt-4">
                <form action="{{ route('bank.add.post') }}" method="POST" onsubmit="return confirm('Hệ thống sẽ không chịu trách nhiệm nếu thông tin thẻ sai. Bạn xác nhận thêm thẻ này?')">
                    @csrf
                    <div class="form-header">
                        <img src="{{ asset('image/pay.png') }}" alt="Pay">
                        <span>Chọn loại thẻ / Ví điện tử</span>
                    </div>
                    <div class="alert alert-primary mb-0 label-bank-choose cursor-pointer">Thẻ:</div>
                    <div class="form-group d-flex flex-wrap justify-content-center box-bank-choose">
                        @foreach(config('withdraw.bank') as $key => $name)
                            <label class="box-card" for="bank-{{ $key }}">
                                <img src="{{ asset('image/bank/' . $key . '.png') }}" alt="{{ $key }}">
                                <input type="radio" id="bank-{{ $key }}" name="bank_select" value="bank_{{ $key }}">
                                <span class="checkbox-custom"></span>
                            </label>
                        @endforeach
                    </div>
                    <div class="alert alert-primary mb-0 label-bank-choose cursor-pointer">Ví điện tử:</div>
                    <div class="form-group d-flex flex-wrap justify-content-center box-bank-choose">
                        @foreach(config('withdraw.wallet') as $key => $name)
                            <label class="box-card" for="wallet-{{ $key }}">
                                <img src="{{ asset('image/bank/' . $key . '.png') }}" alt="{{ $key }}">
                                <input type="radio" id="wallet-{{ $key }}" name="bank_select" value="wallet_{{ $key }}">
                                <span class="checkbox-custom"></span>
                            </label>
                        @endforeach
                    </div>
                    <hr />
                    <div class="form-header">
                        <img src="{{ asset('image/pay.png') }}" alt="Pay">
                        <span>Số tài khoản / Số điện thoại ví</span>
                    </div>
                    <div class="form-group d-flex flex-wrap justify-content-center mt-2">
                        <input class="form-control" name="account_number" placeholder="Số tài khoản" autocomplete="off">
                    </div>
                    <hr />
                    <div class="form-header">
                        <img src="{{ asset('image/pay.png') }}" alt="Pay">
                        <span>Tên chủ tài khoản</span>
                    </div>
                    <div class="form-group d-flex flex-wrap justify-content-center mt-2">
                        <input class="form-control" name="account_name" placeholder="Tên chủ khoản" autocomplete="off">
                    </div>
                    <hr />
                    <div class="footer-button d-flex justify-content-center">
                        <button class="btn btn-primary">Thêm</button>
                        <a class="btn btn-secondary ml-2" href="{{ route('bank.list') }}">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
