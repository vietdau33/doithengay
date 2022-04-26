@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Lịch sử mua thẻ</p>
        </div>
        <div class="body-content">
            <div class="box-content box-pay-card p-3 mt-4">
                <table class="table table-responsive text-center custom-scrollbar">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Loại thẻ</th>
                            <th scope="col">Mệnh giá</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Chiết khấu</th>
                            <th scope="col">Tiền trả</th>
                            <th scope="col">Ngày mua</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($histories as $history)
                            <tr>
                                <th scope="row">
                                    @if($history->type_buy == 'fast' || $history->status === 2)
                                        <a href="{{ route('list-card', ['hash' => $history->store_hash]) }}" class="text-decoration-none">Xem</a>
                                    @endif
                                </th>
                                <td style="min-width: 130px;">{{ ucfirst($history->card_buy) }}</td>
                                <td style="min-width: 150px;">{{ number_format($history->money_buy) }}</td>
                                <td style="min-width: 70px;">{{ $history->quantity }}</td>
                                <td style="min-width: 140px;">{!! $history->getStatus() !!}</td>
                                <td style="min-width: 70px;">{{ $history->rate_buy ?? 0 }}%</td>
                                <td style="min-width: 70px;">{{ number_format($history->money_after_rate ?? $history->money_buy) }}</td>
                                <td style="min-width: 170px;">{{ date('H:i d/m/Y', strtotime($history->created_at)) }}</td>
                            </tr>
                        @endforeach
                        @if($histories->count() == 0)
                            <tr>
                                <td colspan="5" style="min-width: 615px;">Không có lịch sử</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
