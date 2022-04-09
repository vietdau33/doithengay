@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Chiết khấu đổi thẻ</p>
        </div>
        <div class="box-content box-pay-card p-3 mt-4">
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
                                <th scope="col">Chiết khấu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($stt = 1)
                            @php($rateCompare = config('card.rate-compare'))
                            @foreach($rate as $r)
                                <tr>
                                    <th scope="row">{{ $stt++ }}</th>
                                    <td>{{ number_format($r['price']) }}</td>
                                    <td>{{ $r['rate'] + $rateCompare }}%</td>
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
