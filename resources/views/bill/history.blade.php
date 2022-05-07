@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Lịch sử thanh toán cước</p>
        </div>
        <div class="body-content">
            <div class="box-content box-pay-card p-3 mt-4">
                <table class="table table-responsive text-center custom-scrollbar">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Loại thanh toán</th>
                            <th scope="col">Loại tài khoản</th>
                            <th scope="col">Số tiền</th>
                            <th scope="col">Thực trả</th>
                            <th scope="col">Mã KH / SĐT</th>
                            <th scope="col">Ngày tạo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($stt = 1)
                        @foreach($bills as $bill)
                            <tr>
                                <th scope="row" style="vertical-align: middle">{{ $stt++ }}</th>
                                <td style="min-width: 120px;vertical-align: middle;">{!! $bill->getStatusHtml() !!}</td>
                                <td style="min-width: 200px;vertical-align: middle">{{ config("bill.{$bill->type}.text") }}</td>
                                <td style="min-width: 150px;vertical-align: middle">{{ config("bill.{$bill->type}.vendor.{$bill->vendor_id}.name") }}</td>
                                <td style="min-width: 140px;vertical-align: middle">{{ number_format($bill->money) }}</td>
                                <td style="min-width: 140px;vertical-align: middle">{{ number_format($bill->money_after_rate) }}</td>
{{--                                <td style="min-width: 130px;">{{ config("payment.method.{$bill->type_pay}.name") }}</td>--}}
                                <td style="min-width: 130px;vertical-align: middle">{{ $bill->bill_number }}</td>
                                <td>{{ date('H:i d/m/Y', strtotime($bill->created_at)) }}</td>
                            </tr>
                        @endforeach
                        @if($bills->count() == 0)
                            <tr>
                                <td colspan="7" style="min-width: 615px;">Không có lịch sử</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <hr />
                <div class="text-right">
                    <a href="{{ route('pay-bill') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </div>
        </div>
    </div>
@endsection
