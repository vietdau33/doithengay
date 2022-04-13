@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Tạo đơn thanh toán cước</p>
        </div>
        <div class="body-content">
            <div class="box-content box-pay-card p-3 mt-4">
                <div class="form-group d-flex justify-content-between align-items-center">
                    <h4 class="font-weight-bold mb-0">{{ config('bill')[$type]['text'] }}</h4>
                    <a class="btn btn-secondary ml-2" href="{{ route('pay-bill') }}">Quay lại</a>
                </div>
                <hr />
                <form action="{{ route('pay-bill.post') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div class="form-header">
                        <img src="{{ asset('image/pay.png') }}" alt="Pay">
                        <span>Chọn loại tài khoản</span>
                    </div>
                    <div class="form-group d-flex flex-wrap justify-content-center ">
                        @php($vendor = config('bill')[$type]['vendor'])
                        @foreach($vendor as $key => $_v)
                            @continue(!in_array($key, $billActive))
                            @php($checked = count($vendor) == 1 || old('vendor_id') == $key)
                            <label class="box-card {{ $checked ? 'checked' : '' }}" for="vendor_id-{{ $key }}">
                                <img src="{{ asset($_v['image']) }}" alt="{{ $key }}">
                                <input type="radio" id="vendor_id-{{ $key }}" name="vendor_id" value="{{ $key }}" {{ $checked ? 'checked' : '' }}>
                                <span class="checkbox-custom"></span>
                            </label>
                        @endforeach
                    </div>
                    <hr>
                    <div class="form-header">
                        <img src="{{ asset('image/pay.png') }}" alt="Pay">
                        <span>Chọn số tiền cần thanh toán</span>
                    </div>
                    <div class="form-group d-flex flex-wrap justify-content-center ">
                        @foreach([10000, 20000, 30000, 50000, 100000, 200000, 300000, 500000] as $money)
                            <label class="box-card" for="money-{{ $money }}">
                                <input type="radio" id="money-{{ $money }}" name="money_buy" value="{{ $money }}" {{ old('money_buy') != $money ?: 'checked' }}>
                                <span>{{ number_format($money) }} Đ</span>
                                <span class="checkbox-custom"></span>
                            </label>
                        @endforeach
                        <input type="text" class="form-control" name="money" value="{{ old('money') }}" placeholder="Số tiền thanh toán">
                    </div>

                    <hr />
                    <div class="form-header">
                        <img src="{{ asset('image/pay.png') }}" alt="Pay">
                        @php($text = in_array($type, ['main_account', 'prepaid_mobile', 'postpaid_mobile']) ? 'Số điện thoại' : 'Mã khách hàng')
                        <span>{{ $text }}</span>
                    </div>
                    <div class="form-group d-flex flex-wrap justify-content-center mt-2">
                        <input class="form-control" name="bill_number" value="{{ old('bill_number') }}" placeholder="{{ $text }}">
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
                                    name="type_pay"
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
        $('[name="money_buy"]').on('change', function() {
            let val = $(this).val().trim();
            $('[name="money"]').val(val).trigger('blur');
        });
        $('[name="money"]').on('input', function() {
            $('[name="money_buy"]:checked').prop('checked', false).closest('label').removeClass('checked');
            let val = $(this).val().trim();
            $('[name="money_buy"][value="' + val + '"]').prop('checked', true).closest('label').addClass('checked');
        });
        $('[name="money"]').on('focus', function(){
            let val = $(this).val();
            $(this).val(val.split(',').join(''));
        });
        $('[name="money"]').on('blur', function(){
            let val = $(this).val();
            $(this).val(App.setPriceFormat(val));
        });
        $('[name="money"]').trigger('change');
    </script>
@endsection
