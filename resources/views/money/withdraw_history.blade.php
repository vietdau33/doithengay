@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Lịch sử yêu cầu rút tiền</p>
        </div>
        <div class="body-content">
            <div class="box-content box-pay-card p-3 mt-4">
                <table class="table table-hover table-responsive text-center custom-scrollbar">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Ngân hàng</th>
                        <th scope="col">Số tiền</th>
                        <th scope="col">Ghi chú</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php($stt = 1)
                        @foreach($histories as $history)
                            @php($bank = $history->bank_relation)
                            <tr>
                                <th scope="row">{{ $stt++ }}</th>
                                <td style="min-width: 150px;">{!! $history->getStatus() !!}</td>
                                <td style="min-width: 130px;">{{ date('d/m/Y', strtotime($history->created_at)) }}</td>
                                <td style="min-width: 250px">{{ $bank->account_number }} ({{ getNameBank($bank->type, $bank->name) }} - {{ $bank->account_name }})</td>
                                <td style="min-width: 180px;">{{ number_format($history->money) }} VNĐ</td>
                                <td style="min-width: 200px;">{{ $history->note }}</td>
                            </tr>
                        @endforeach
                        @if($histories->count() == 0)
                            <tr>
                                <td colspan="6" style="min-width: 615px;">Không có lịch sử</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="add-new text-right">
                    <a href="{{ route('withdraw') }}" class="btn btn-success">Tạo yêu cầu</a>
                </div>
            </div>
        </div>
    </div>
@endsection
