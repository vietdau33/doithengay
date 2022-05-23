@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Chiết khấu đổi thẻ</p>
        </div>
        <div class="box-content box-pay-card p-3 mt-4" style="max-width: 750px;">
            @foreach($rates as $name => $rate)
                <div class="form-header">
                    <img src="{{ asset('image/pay.png') }}" alt="Pay">
                    <span class="font-weight-bold">{{ ucfirst($name) }}</span>
                </div>
                <div class="form-group d-flex flex-wrap justify-content-center">
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Mệnh giá</th>
                                @if(user()->type_user == 'nomal')
                                    <th scope="col">Chiết khấu gạch nhanh</th>
                                    <th scope="col">Chiết khấu gạch chậm</th>
                                @else
                                    <th scope="col">Chiết khấu</th>
                                @endif
                                <th scope="col">Cập nhật</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($stt = 1)
                            @foreach($rate as $r)
                                <tr>
                                    <th scope="row">{{ $stt++ }}</th>
                                    <td>{{ number_format($r['price']) }}</td>
                                    @if(user()->type_user == 'nomal')
                                        <td>{{ $r['rate_use'] }}%</td>
                                        <td>{{ $r['rate_slow'] }}%</td>
                                    @elseif(user()->type_user == 'daily')
                                        <td>{{ $r['rate_daily'] }}%</td>
                                    @elseif(user()->type_user == 'tongdaily')
                                        <td>{{ $r['rate_tongdaily'] }}%</td>
                                    @endif
                                    <td>{{ date('H:i d/m/Y', strtotime($r['updated_at'])) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr />
            @endforeach
        </div>
    </div>
@endsection
