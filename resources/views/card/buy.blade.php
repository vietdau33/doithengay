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
                    @if(false)
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
                    @else
                        <input type="hidden" name="method_buy" value="cash" />
                    @endif
                    <hr />
                    <div class="form-header row">
                        <div class="col-12 col-sm-4">
                            <img src="{{ asset('image/pay.png') }}" alt="Pay">
                            <span>Số lượng</span>
                        </div>
                        <div class="col-12 col-sm-8">
                            <div class="form-group d-flex flex-wrap justify-content-center m-0">
                                <div class="input-group">
                                    <input type="button" value="-" class="btn button-minus" data-field="quantity">
                                    <input type="number" step="1" value="{{ old('quantity', '1') }}" name="quantity" class="quantity-field form-control d-inline-block flex-grow-0">
                                    <input type="button" value="+" class="btn button-plus" data-field="quantity">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="form-header">
                        <img src="{{ asset('image/pay.png') }}" alt="Pay">
                        <span>Phương thức xử lý</span>
                    </div>
                    <div class="form-group d-flex flex-wrap justify-content-center mt-2">
                        <label class="box-card" for="type-slow">
                            <input
                                type="radio"
                                id="type-slow"
                                name="type_buy"
                                value="slow"
                                {{ old('type_buy') == 'slow' ? 'checked' : '' }}
                            />
                            <span>Mua chậm</span>
                            <span class="checkbox-custom"></span>
                        </label>
                        <label class="box-card" for="type-fast">
                            <input
                                type="radio"
                                id="type-fast"
                                name="type_buy"
                                value="fast"
                                {{  old('type_buy') != 'slow' ? 'checked' : '' }}
                            />
                            <span>Mua nhanh</span>
                            <span class="checkbox-custom"></span>
                        </label>
                    </div>
                    <hr />
                    <div class="alert alert-warning">
                        <ul style="list-style: decimal; padding-left: 20px">
                            <li>Đối với <b>mua chậm</b>, thời gian xác minh thẻ tối đa là <b>5 phút</b>.</li>
                            <li>Sau <b>5 phút</b>, nếu hệ thống không xử lý được thẻ thì thẻ sẽ bị đẩy sang <b>mua thường</b>.</li>
                        </ul>
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
@section('script')
    <script>
        function incrementValue(e) {
            e.preventDefault();
            let fieldName = $(e.target).data('field');
            let parent = $(e.target).closest('div');
            let currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

            if (!isNaN(currentVal)) {
                parent.find('input[name=' + fieldName + ']').val(currentVal + 1);
            } else {
                parent.find('input[name=' + fieldName + ']').val(0);
            }
        }

        function decrementValue(e) {
            e.preventDefault();
            let fieldName = $(e.target).data('field');
            let parent = $(e.target).closest('div');
            let currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

            if (!isNaN(currentVal) && currentVal > 1) {
                parent.find('input[name=' + fieldName + ']').val(currentVal - 1);
            } else {
                parent.find('input[name=' + fieldName + ']').val(1);
            }
        }

        $('.input-group').on('click', '.button-plus', function(e) {
            incrementValue(e);
        });

        $('.input-group').on('click', '.button-minus', function(e) {
            decrementValue(e);
        });
    </script>
@endsection
