@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Lịch sử đổi thẻ</p>
        </div>
        <div class="body-content">
            <div class="box-content box-pay-card p-3 mt-4">
                <table class="table table-hover table-responsive text-center custom-scrollbar">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Loại thẻ</th>
                            <th scope="col">Số tiền</th>
                            <th scope="col">Thực nhận</th>
                            <th scope="col">Số seri</th>
                            <th scope="col">Số thẻ</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Ngày yêu cầu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($stt = 1)
                        @php($cardTrade = get_card_trade())
                        @foreach($histories as $history)
                            @php($realMoney = $history->status == 3 ? json_decode($history->contents, 1)['real'] : 0)
                            <tr>
                                <th scope="row">{{ $stt++ }}</th>
                                <td style="min-width: 130px;">{{ $cardTrade[$history->card_type]['name'] }}</td>
                                <td style="min-width: 150px;">{{ number_format($history->card_money) }}</td>
                                <td style="min-width: 150px;">{{ number_format($realMoney) }}</td>
                                <td style="min-width: 200px;">{{ $history->card_serial }}</td>
                                <td style="min-width: 200px;">{{ $history->card_number }}</td>
                                <td style="min-width: 200px;">{!! $history->getStatusHtml() !!}</td>
                                <td style="min-width: 200px;">{{ date('d/m/Y', strtotime($history->created_at)) }}</td>
                            </tr>
                        @endforeach
                        @if($histories->count() == 0)
                            <tr>
                                <td colspan="8" style="min-width: 615px;">Không có lịch sử</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
