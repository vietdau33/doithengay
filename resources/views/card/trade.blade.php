@extends('layout')
@section('contents')
    <div class="container-fluid" id="auth-content">
        <div class="header-content d-flex justify-content-center flex-column">
            <img src="{{ asset('image/icon/page.svg') }}" alt="Phone" class="mb-2">
            <p class="m-0 font-weight-bold">Đổi thẻ cào</p>
        </div>
        <div class="body-content">
            <div class="box-content box-pay-card p-3 mt-4">
                <div class="form-group d-flex justify-content-between align-items-center">
                    <h4 class="font-weight-bold mb-0">Đổi thẻ cào</h4>
                    <a href="{{ route('trade-card.history') }}" class="btn btn-secondary">Xem lịch sử</a>
                </div>
                <hr />
                <div class="alert alert-warning">
                    <p class="font-weight-bold">Lưu ý:</p>
                    <ul style="list-style: decimal; padding-left: 20px">
                        <li>Bạn phải chọn chính xác <b>loại thẻ</b> và <b>mệnh giá</b> thẻ cào.</li>
                        <li>Nếu chọn sai loại thẻ, hệ thống sẽ <b>từ chối</b> yêu cầu gạch thẻ, thẻ cào sẽ bị <b>vô hiệu hóa</b>.</li>
                        <li>Nếu chọn sai mệnh giá, hệ thống sẽ <b>trừ 50%</b> số tiền nhận được.</li>
                    </ul>
                </div>
                <hr />
                <form action="{{ route('trade-card.post') }}" method="POST">
                    @csrf
                    <div class="form-header">
                        <img src="{{ asset('image/pay.png') }}" alt="Pay">
                        <span>Chọn loại thẻ</span>
                    </div>
                    <div class="form-group d-flex flex-wrap justify-content-center" id="area-card">
                        @foreach($rates as $key => $card)
                            <label class="box-card" for="card-{{ $key }}">
                                <img src="/image/card/{{ $key }}.png" alt="{{ $key }}">
                                <input type="radio" id="card-{{ $key }}" name="card_type" data-type="{{ $key }}" value="{{ $rateID[$key] }}" {{ old('card_type') == $rateID[$key] ? 'checked' : '' }}>
                                <span class="checkbox-custom"></span>
                            </label>
                        @endforeach
                    </div>
                    <hr />
                    <div class="form-header">
                        <img src="{{ asset('image/pay.png') }}" alt="Pay">
                        <span>Chọn mệnh giá</span>
                    </div>
                    <div class="form-group d-flex flex-wrap justify-content-center" id="area-money"></div>
                    <hr />
                    <div class="form-group d-flex flex-wrap justify-content-center mt-2">
                        <label for="card_serial" class="ignore w-100 text-left">Số seri:</label>
                        <input class="form-control" name="card_serial" id="card_serial" value="{{ old('card_serial') }}" placeholder="Số seri" autocomplete="off">
                    </div>
                    <div class="form-group d-flex flex-wrap justify-content-center mt-2">
                        <label for="card_number" class="ignore w-100 text-left">Mã thẻ:</label>
                        <input class="form-control" name="card_number" id="card_number" value="{{ old('card_number') }}" placeholder="Mã thẻ" autocomplete="off">
                    </div>
                    <hr />
                    <div class="footer-button d-flex justify-content-center">
                        <button class="btn btn-primary">Gửi yêu cầu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <label class="box-card d-none" for="" data-template="label-money">
        <input type="radio" id="" name="card_money" value="">
        <span class="money-show"></span>
        <span class="checkbox-custom"></span>
    </label>
@endsection
@section('script')
    <script>
        let rates = {!! json_encode($rates) !!};
        let areaMoney = $('#area-money');
        let template = $('[data-template="label-money"]');
        let rate_id = App.objectFlip({!! json_encode($rateID) !!});

        $('[name="card_type"]').on('change', function(){
            let type    = rate_id[$(this).val()];
            let rate    = rates[type];

            areaMoney.empty();

            if(rate == undefined) {
                console.log(type, rate)
                return;
            }

            $.each(rate, function(index, r){
                let tempEl  = template.clone().removeClass('d-none').removeAttr('data-template');
                let money = r.price;
                let id = 'money-' + money;
                tempEl.attr('for', id);
                tempEl.find('input').attr('id', id).attr('value', money)
                tempEl.find('.money-show').text(App.setPriceFormat(money));
                areaMoney.append(tempEl);
            });
        });

        if($('#area-card').find('[name="card_type"]:checked').length > 0){
            $('#area-card').find('[name="card_type"]:checked').trigger('change');
        }else{
            $('#area-card').find('label.box-card:first-child').trigger('click');
        }
    </script>
@endsection
