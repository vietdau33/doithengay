@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Thanh toán cước</p>
        </div>
        <div class="body-content">
            <div class="box-content box-pay-card p-3 mt-4">
                <div class="form-group d-flex justify-content-between align-items-center">
                    <h4 class="font-weight-bold mb-0"></h4>
                    <a href="{{ route('pay-bill.history') }}" class="btn btn-secondary">Xem lịch sử</a>
                </div>
                <hr />
                <div class="area-bill">
                    <ul>
                        @php($configBill = config('bill'))
                        @foreach($bills as $key => $bill)
                            <li>
                                <a href="{{ route('pay-bill.create', ['type' => $key]) }}" style="--background: url('{{ $configBill[$key]['image'] }}')">
                                    <div class="icon"></div>
                                    <div class="text">{{ $configBill[$key]['text'] }}</div>
                                    <span class="checkbox-custom"></span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
