@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Rút tiền</p>
        </div>
        <div class="body-content">
            <div class="box-content text-left p-3 mt-4">
                <form action="{{ route('withdraw.post') }}" method="POST" onsubmit="return confirm('Bạn xác nhận thông tin yêu cầu là chính xác?')">
                    @csrf
                    <div class="header-box d-flex justify-content-between align-items-center">
                        <p class="mb-0">Số dư hiện tại: {{ number_format(user()->money) }} VNĐ</p>
                        <a href="{{ route('withdraw.history') }}" class="btn btn-secondary">Xem lịch sử</a>
                    </div>
                    <hr>
                    <div class="alert alert-warning">Khi thực hiện yêu cầu rút tiền, số tiền ít nhất là 100.000đ</div>
                    <hr>
                    <div class="row m-0">
                        <label class="w-100 font-weight-bold" for="bank">Ngân hàng *</label>
                        @if($banks->count() == 0)
                            <p class="alert alert-warning w-100">Bạn cần thêm thẻ trước. Đi tới <a href="{{ route('bank.add') }}">Thêm Thẻ</a></p>
                        @endif
                        <select name="bank" id="bank" class="form-control">
                            <option value="">Chọn thẻ rút về</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->id }}">
                                    {{ $bank->account_number }} ({{ getNameBank($bank->type, $bank->name) }} - {{ $bank->account_name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row m-0 mt-3">
                        <label class="w-100 font-weight-bold" for="money">Số tiền *</label>
                        <input type="text" class="form-control" name="money" id="money" placeholder="Số tiền">
                    </div>
                    <div class="row m-0 mt-3">
                        <label class="w-100 font-weight-bold" for="note">Ghi chú *</label>
                        <textarea cols="3" rows="2" class="form-control autosize" name="note" id="note" placeholder="Ghi chú">Rút tiền từ hệ thống {{ strtoupper(request()->getHost()) }}</textarea>
                    </div>
                    <div class="row m-0 mt-3 justify-content-center ">
                        <button class="btn btn-primary">Gửi yêu cầu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
