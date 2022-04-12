@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Mua thẻ cào</p>
        </div>
        <div class="body-content">
            <div class="box-content box-pay-card p-3 mt-4">
                <div class="form-group d-flex justify-content-between align-items-center">
                    <h4 class="font-weight-bold mb-0">Mua thẻ cào</h4>
                    <a href="{{ route('buy-card.history') }}" class="btn btn-secondary">Xem lịch sử</a>
                </div>
                <hr />
                <form action="{{ route('buy-card.post') }}" method="POST">
                    @csrf
                    <div class="form-header">
                        <img src="{{ asset('image/pay.png') }}" alt="Pay">
                        <span>Chọn loại thẻ</span>
                    </div>
                    <div class="form-group d-flex flex-wrap justify-content-center ">
                        @foreach($listCard as $card)
                            <label class="box-card" for="card-{{ $card['name'] }}">
                                <img src="/image/card/{{ $card['name'] }}.png" alt="{{ $card['name'] }}">
                                <input type="radio" id="card-{{ $card['name'] }}" name="card_buy" value="{{ $card['name'] }}" {{ old('card_buy') != $card['name'] ?: 'checked' }}>
                                <span class="checkbox-custom"></span>
                            </label>
                        @endforeach
                    </div>
                    <hr />
                    <div class="form-header">
                        <img src="{{ asset('image/pay.png') }}" alt="Pay">
                        <span>Chọn mệnh giá</span>
                    </div>
                    <div class="form-group d-flex flex-wrap justify-content-center ">
                        @foreach([10000, 20000, 30000, 50000, 100000, 200000, 300000, 500000] as $money)
                            <label class="box-card" for="money-{{ $money }}">
                                <input type="radio" id="money-{{ $money }}" name="money_buy" value="{{ $money }}" {{ old('money_buy') != $money ?: 'checked' }}>
                                <span>{{ number_format($money) }} Đ</span>
                                <span class="checkbox-custom"></span>
                            </label>
                        @endforeach
                    </div>
                    <hr />
                    <div class="form-header">
                        <img src="{{ asset('image/pay.png') }}" alt="Pay">
                        <span>Phương thức thanh toán</span>
                    </div>
                    <div class="form-group d-flex flex-wrap justify-content-center mt-2">
                        @foreach(config('payment.method') as $key => $method)
                            <label class="box-card" for="method-{{ $key }}">
                                <input
                                    type="radio"
                                    id="method-{{ $key }}"
                                    name="method_buy"
                                    value="{{ $key }}"
                                    {{ $method['default'] && old('method_buy') == null ? 'checked' : '' }}
                                    {{ old('method_buy') != $key  ?: 'checked'}}
                                />
                                <span>{{ $method['name'] }}</span>
                                <span class="checkbox-custom"></span>
                            </label>
                        @endforeach
                    </div>
                    <hr />
                    <div class="form-header">
                        <img src="{{ asset('image/pay.png') }}" alt="Pay">
                        <span>Số lượng</span>
                    </div>
                    <div class="form-group d-flex flex-wrap justify-content-center mt-2">
                        <input class="form-control" name="quantity" value="{{ old('quantity', '1') }}" placeholder="Số lượng">
                    </div>
                    <hr />
                    <div class="footer-button d-flex justify-content-center">
                        <button class="btn btn-primary">Tiếp tục</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
