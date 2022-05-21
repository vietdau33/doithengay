@extends('layout')
@section('contents')
    <div class="container-fluid">
        <h4 class="font-weight-bold mb-0 mt-4 text-center">Mua thẻ cào</h4>
        <div class="row body-content">
            <div class="col-md-7 col-12 box-pay-card mt-4">
                <form action="{{ route('buy-card.post') }}" method="POST" class="box-shadow p-2">
                    @csrf
                    <div class="form-header">
                        <img src="{{ asset('image/pay.png') }}" alt="Pay">
                        <span>Chọn loại thẻ</span>
                    </div>
                    <div class="form-group form-group-card-buy d-flex flex-wrap justify-content-center">
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

                    @php($dataActiveMoney = json_encode(['money_buy' => old('money_buy', ''), 'card_buy' => old('card_buy', '')]))
                    <div class="form-group d-flex flex-wrap justify-content-center" id="area-money" data-active="{{ $dataActiveMoney }}"></div>
                    <input type="hidden" name="method_buy" value="cash" />
                </form>
            </div>
            <div class="col-md-5 col-12 mt-4">
                <div class="box-card-store box-shadow p-2">
                    <div class="heading d-flex align-items-center mt-2">
                        <i class="fa fa-shopping-cart"></i>
                        <h6 class="mb-0 ml-2 font-weight-bold">Giỏ hàng</h6>
                    </div>
                    <hr>
                    <div class="body-cart">
                        <span class="cart-empty font-italic">Giỏ hàng trống</span>
                        <div class="row-cart row-cart-template d-none flex-wrap justify-content-between align-items-center mb-2">
                            <div class="cart-info"></div>
                            <div class="cart-quantity">
                                <div class="input-group position-relative">
                                    <input
                                        type="number"
                                        step="1"
                                        value="{{ old('quantity', '1') }}"
                                        name="quantity"
                                        onchange="if(parseInt(this.value) < 1) this.value=1"
                                        style="background: #fff !important;"
                                        class="quantity-field form-control d-inline-block flex-grow-0 p-1 text-left"
                                    />
                                    <button class="btn button-minus" data-field="quantity">-</button>
                                    <button class="btn button-plus" data-field="quantity">+</button>
                                </div>
                            </div>
                            <div class="cart-remove">
                                <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                            </div>
                            <div class="cart-type-buy mt-2" style="width: 40%">
                                <select name="" id="" class="form-control">
                                    <option value="">Phương thức mua thẻ</option>
                                    <option value="slow">Mua chậm - Chiết khấu: 0%</option>
                                    <option value="fast">Mua nhanh - Chiết khấu: 0%</option>
                                </select>
                            </div>
                            <div class="cart-money text-right" style="width: 30%"></div>
                        </div>
                    </div>
                    <div class="total-money-cart">
                        <hr>
                        <div class="alert alert-warning alert-result-buy-card d-none">
                            <ul class="pl-3 mb-0"></ul>
                        </div>
                        <p class="m-0 text-right">Tổng: <span class="total-money-cart-text">0</span>đ</p>
                    </div>
                    @if(user()->security_level_2 === 1)
                        <hr>
                        <div class="row row-otp-buy-card m-0 mt-3 d-none">
                            <label class="w-100 text-left ignore" for="otp_code">OTP Code *</label>
                            <div class="d-flex w-100">
                                <input type="hidden" class="form-control" name="otp_hash" id="otp_hash" value="{{ old('otp_hash') }}">
                                <input type="text" class="form-control" name="otp_code" id="otp_code" value="{{ old('otp_code') }}" placeholder="123456">
                                <div class="btn btn-primary ml-2 send-otp" style="min-width: 110px;">Send OTP</div>
                            </div>
                        </div>
                    @endif
                    <div class="payment-cart text-center">
                        <hr class="mb-2">
                        <button class="btn btn-primary">Thanh toán</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-warning mt-3">
            <ul style="list-style: decimal; padding-left: 20px">
                <li>Đối với <b>mua chậm</b>, thời gian xác minh thẻ tối đa là <b>2 phút</b>.</li>
                <li>Sau <b>2 phút</b>, nếu hệ thống không xử lý được thẻ thì thẻ sẽ bị đẩy sang <b>mua nhanh</b>.</li>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <h5>Lịch sử mua thẻ</h5>
        <div class="row-filter d-flex">
            <select name="filter_card_buy" class="form-control mr-2">
                <option value="">Loại thẻ</option>
                @foreach($listCard as $type => $card)
                    <option value="{{ $type }}">{{ ucfirst($card['name']) }}</option>
                @endforeach
            </select>
            <select name="filter_money_buy" class="form-control mr-2">
                <option value="">Mệnh giá</option>
            </select>
            <input type="text" name="filter_from_date" class="form-control mr-2" value="{{ date('Y-m-d') }}" data-date-picker>
            <input type="text" name="filter_to_date" class="form-control mr-2" value="{{ date('Y-m-d') }}" data-date-picker>
            <button class="btn btn-success btn-filter" style="min-width: 150px;">Lọc dữ liệu</button>
        </div>
        <div class="table-filter mt-2">
            @include('card.buy_history_table', ['histories' => $histories])
        </div>
    </div>
    <div data-template="label-money" class="div-box-card d-none">
        <label class="box-card mb-1" for="">
            <input type="checkbox" id="" name="money_buy" value="">
            <span class="money-show"></span>
            <span class="checkbox-custom"></span>
        </label>
        <p class="real-money-slow mb-2 text-center">Giá: <span style="color: #c0201e">0đ</span></p>
    </div>
@endsection
@section('script')
    <script>
        let rates = {!! json_encode($rates) !!};
        let areaMoney = $('#area-money');
        let template = $('[data-template="label-money"]');
        let listNotAuto = {!! json_encode($listNotAuto) !!};

        let activeMoney = function() {
            let dataActiveMoney = JSON.parse(areaMoney.attr('data-active'));
            if(dataActiveMoney.card_buy == '' || dataActiveMoney.money_buy == '') {
                return;
            }
            if($(`[name="card_buy"][value="${dataActiveMoney.card_buy}"]`).prop('checked') === true) {
                $(`[name="money_buy"][value="${dataActiveMoney.money_buy}"]`).trigger('click');
            }
        }

        $('[name="card_buy"]').on('change', function(){
            let val = $(this).val();
            let rate = rates[val];
            areaMoney.empty();
            if(rate == undefined) {
                return;
            }
            $.each(rate, function(index, r){
                let tempEl  = template.clone().removeClass('d-none').removeAttr('data-template');
                let money = r.price;
                let id = 'money-' + money;
                let moneySlow = parseInt(money) - (parseInt(money) * parseFloat(r.rate_slow) / 100);
                tempEl.find('label')
                    .attr('for', id);
                tempEl.find('label').find('.money-show')
                    .text(App.setPriceFormat(money));
                tempEl.find('label').find('input')
                    .attr('id', id)
                    .attr('value', money)
                    .attr('data-card', val)
                    .attr('data-rate', r.rate)
                    .attr('data-rate-slow', r.rate_slow);
                tempEl.find('.real-money-slow span').text(App.setPriceFormat(moneySlow) + 'đ');
                areaMoney.append(tempEl);
            });

            $('.body-cart .row-cart:not(.row-cart-template)[data-card="'+val+'"]').each(function(){
                const money = $(this).attr('data-money');
                areaMoney.find('[value="'+money+'"]').addClass('no-action').trigger('click');
            })

            activeMoney();
        });

        setTimeout(function(){
            $('[name="card_buy"]:checked').trigger('change');
        }, 100);
    </script>
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

            parent.find('input[name=' + fieldName + ']').trigger('change');
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

            parent.find('input[name=' + fieldName + ']').trigger('change');
        }
        function calcTotalMoneyCart() {
            let total = 0;
            $('.body-cart .row-cart:not(.row-cart-template)').each(function() {
                const money = $(this).find('.cart-money').attr('data-money');
                if(money != undefined) {
                    total += parseFloat(money);
                }
            });
            $('.total-money-cart-text').text(App.setPriceFormat(total));
        }

        $('.body-cart').on('click', '.button-plus', function(e) {
            incrementValue(e);
        });

        $('.body-cart').on('click', '.button-minus', function(e) {
            decrementValue(e);
        });

        $('#area-money').on('change', 'input[name="money_buy"]', function(){
            if($(this).hasClass('no-action')) {
                $(this).removeClass('no-action');
                return;
            }

            const card = $(this).attr('data-card');
            const rate = parseFloat($(this).attr('data-rate'));
            const rateSlow = parseFloat($(this).attr('data-rate-slow'));
            const money = parseInt($(this).val());
            const cartTemplate = $('.row-cart-template').clone();
            const bodyCard = $('.body-cart');

            if($(this).prop('checked') === false) {
                $('.body-cart').find('.row-cart[data-money="'+money+'"][data-card="'+card+'"]').remove();
                if($('.body-cart').find('.row-cart:not(.row-cart-template)').length <= 0) {
                    $('.body-cart .cart-empty').removeClass('d-none')
                    $('.row-otp-buy-card').addClass('d-none')
                }
                calcTotalMoneyCart();
                return;
            }

            cartTemplate.removeClass('d-none row-cart-template').addClass('d-flex');
            cartTemplate.attr('data-card', card);
            cartTemplate.attr('data-money', money);
            cartTemplate.attr('data-rate', rate);
            cartTemplate.attr('data-rate-slow', rateSlow);
            cartTemplate.find('.cart-info').text(App.ucFirst(card) + ' - ' + App.setPriceFormat(money) + 'đ');
            cartTemplate.find('option[value="slow"]').text('Mua chậm - chiết khấu: ' + rateSlow + '%');
            cartTemplate.find('option[value="fast"]').text('Mua nhanh - chiết khấu: ' + rate + '%');
            cartTemplate.find('.cart-type-buy select').val('slow');
            setTimeout(function(){
                cartTemplate.find('.cart-type-buy select').trigger('change');
            }, 10);
            if(listNotAuto.indexOf(card) != -1) {
                cartTemplate.find('option[value="fast"]').addClass('d-none');
            }
            bodyCard.find('.cart-empty').addClass('d-none');
            bodyCard.append(cartTemplate);
            $('.row-otp-buy-card').removeClass('d-none');
        });

        $(".body-cart").on('change', '.cart-type-buy select', function() {
            const typeRate = this.value;
            const rowCard = $(this).closest('.row-cart');
            if(typeRate == '') {
                rowCard.find('.cart-money')
                    .removeAttr('data-money')
                    .text("Thực trả: 0đ");
                calcTotalMoneyCart();
                return;
            }
            const money = parseInt(rowCard.attr('data-money'));
            const rate = parseFloat(rowCard.attr(typeRate == 'slow' ? 'data-rate-slow' : 'data-rate'));
            const quantity = parseInt(rowCard.find('.cart-quantity input').val());
            const realMoney = money - (money * rate / 100);
            rowCard.find('.cart-money')
                .attr('data-money', realMoney * quantity)
                .text("Thực trả: " + App.setPriceFormat(realMoney * quantity) + 'đ');
            calcTotalMoneyCart();
        });
        $(".body-cart").on('change', '.cart-quantity input', function() {
            const rowCard = $(this).closest('.row-cart');
            const typeRate = rowCard.find('.cart-type-buy select').val();
            if(typeRate == '') {
                return;
            }

            const quantity = parseInt(this.value);
            const money = parseInt(rowCard.attr('data-money'));
            const rate = parseFloat(rowCard.attr(typeRate == 'slow' ? 'data-rate-slow' : 'data-rate'));
            const realMoney = money - (money * rate / 100);

            rowCard.find('.cart-money')
                .attr('data-money', realMoney * quantity)
                .text("Thực trả: " + App.setPriceFormat(realMoney * quantity) + 'đ');
            calcTotalMoneyCart();
        });
        $(".body-cart").on('click', '.cart-remove', function() {
            const rowCart = $(this).closest('.row-cart');
            const money = rowCart.attr('data-money');
            const card = rowCart.attr('data-card');
            $('[name="money_buy"][data-card="'+card+'"][value="'+money+'"]').trigger('click');
            rowCart.remove();
            calcTotalMoneyCart();

            if($('.body-cart').find('.row-cart:not(.row-cart-template)').length <= 0) {
                $('.body-cart .cart-empty').removeClass('d-none')
                $('.row-otp-buy-card').addClass('d-none')
            }
        });

        $(document).ready(function(){
            $("[data-date-picker]").datepicker({
                dateFormat : 'yy-mm-dd'
            });
            if($('[name="card_buy"]:checked').length == 0) {
                $('.form-group-card-buy .box-card:first-child [name="card_buy"]').trigger('click');
            }
            $('.payment-cart button').on('click', function(){
                let error = false;
                let datas = [];
                $('.body-cart').find('.row-cart:not(.row-cart-template)').each(function(){
                    if($(this).find('.cart-money').attr('data-money') == undefined) {
                        alertify.alert('Error', 'Có đơn hàng chưa hoàn thành! Hãy kiểm tra và chọn phương thức mua thẻ!');
                        $('.alertify .ajs-header').addClass('alert-danger');
                        error = true;
                        return false;
                    }
                    const card_buy = $(this).attr('data-card');
                    const money_buy = $(this).attr('data-money');
                    const method_buy = $('[name="method_buy"]').val();
                    const quantity = $(this).find('.cart-quantity .quantity-field').val();
                    const type_buy = $(this).find('.cart-type-buy select').val();
                    datas.push({
                        card_buy, money_buy, method_buy, quantity, type_buy
                    });
                });
                if(error) {
                    return false;
                }
                const otp_code = $('[name="otp_code"]').val();
                const otp_hash = $('[name="otp_hash"]').val();
                Request.ajax('{{ route('buy-card-multi.post') }}', { datas, otp_code, otp_hash }, function(result) {
                    if(result.success == false) {
                        if(result.error_buy_card === false) {
                            alertify.alert('Error', result.errorText);
                            return $('.alertify .ajs-header').removeClass('alert-success').addClass('alert-danger');
                        }
                        result.errors = result.errors.map(err => '<li>'+err+'</li>');
                        alertify.alert('Error', "Có thẻ mua không thành công. Kiểm tra note trên màn hình.");
                        $('.alertify .ajs-header').removeClass('alert-success').addClass('alert-danger');
                        $('.alert-result-buy-card').removeClass('d-none');
                        $('.alert-result-buy-card ul').empty().append(result.errors);
                        return;
                    }
                    alertify.alert('Success', "Tất cả các thẻ được mua thành công. Vui lòng kiểm tra lịch sử để lấy mã thẻ!", function () {
                        window.location.reload();
                    });
                    $('.alertify .ajs-header').removeClass('alert-danger').addClass('alert-success');
                })
            });
            $('[name="filter_card_buy"]').on('change', function() {
                const val = $(this).val();
                const filterMoney = $('[name="filter_money_buy"]')
                filterMoney.empty().append('<option value="">Mệnh giá</option>');
                $.each(rates[val], function (index, rate) {
                    const money = rate.price;
                    filterMoney.append('<option value="' + money + '">' + App.setPriceFormat(money) + '</option>')
                });
            });
            $('.btn-filter').on('click', function(){
                const url = '{{ route('buy-card.history.filter') }}';
                const filter_card_buy = $('[name="filter_card_buy"]').val();
                const filter_money_buy = $('[name="filter_money_buy"]').val();
                const filter_from_date = $('[name="filter_from_date"]').val();
                const filter_to_date = $('[name="filter_to_date"]').val();
                Request.ajax(url, { filter_card_buy, filter_money_buy, filter_from_date, filter_to_date }, function(result) {
                    if(result.success) {
                        $('.table-filter').empty().append(result.html)
                    }
                })
            });
        })
    </script>
    <script>
        $('.send-otp').on('click', function(e){
            e.preventDefault();
            if($(this).hasClass('clicked')){
                return false;
            }
            $(this).addClass('clicked');
            $(this).text('Sending...');
            const self = this;
            Request.ajax('{{ route('security.send-otp') }}', function(result) {
                if(!result.success){
                    alertify.alert('Error', result.message);
                    $('.alertify .ajs-header').removeClass('alert-success').addClass('alert-danger');
                    return;
                }
                $('#otp_hash').val(result.data.hash);
                $(self).removeClass('clicked').text('Send OTP');
                alertify.alert('Notification', result.message);
                $('.alertify .ajs-header').removeClass('alert-danger').addClass('alert-success');
            });
        })
    </script>
@endsection
