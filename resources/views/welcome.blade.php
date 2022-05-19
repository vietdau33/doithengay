@php($show_menu_bottom = true)
@extends('layout')
@section('contents')
    @if(!logined())
        <div id="slide-home" class="container-fluid pt-3 pb-3">
            <div class="swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img class="w-100" src="{{ asset('image/slide_home/bannerslide1.jpg') }}" alt="1">
                    </div>
                    <div class="swiper-slide">
                        <img class="w-100" src="{{ asset('image/slide_home/bannerslide2.png') }}" alt="2">
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    @endif
    <div class="container mt-4 mb-4">
        <h3 class="text-center font-weight-bold">ĐỔI THẺ</h3>
        <ul class="list-notif mb-4">
            <li>
                <img src="{{ asset('image/arrow-1.gif') }}" alt="Arrow">
                <span class="font-weight-bold">Chọn chính xác loại thẻ và mệnh giá thẻ cào.</span>
            </li>
			<li>
                <img src="{{ asset('image/arrow-1.gif') }}" alt="Arrow">
                <span class="font-weight-bold">Nếu chọn sai loại thẻ, thẻ cào sẽ bị vô hiệu hóa.</span>
            </li>
            <li>
                <img src="{{ asset('image/arrow-1.gif') }}" alt="Arrow">
                <span class="font-weight-bold">Nếu chọn sai mệnh giá, hệ thống sẽ trừ 50% số tiền nhận được..</span>
            </li>
			<li>
                <img src="{{ asset('image/arrow-1.gif') }}" alt="Arrow">
                <span class="font-weight-bold">Không nhận API game bài, cổng game bài cố tình đấu api sẽ bị khóa.</span>
            </li>
            <li>
                <img src="{{ asset('image/arrow-1.gif') }}" alt="Arrow">
                <span class="font-weight-bold">Hướng dẫn tích hợp API gạch thẻ tự động <a href="{{ route('connect-api') }}">tại đây</a>.</span>
            </li>
        </ul>
    </div>
    <div class="container mb-3 container-trade-card-home">
        <div class="form-group form-trade-card-home position-relative d-none" data-id="template">
            <select name="card_type" class="form-control mb-1 mr-1 select_card_type">
                <option value="">Loại thẻ</option>
                @foreach($cardList as $key => $card)
                    <option value="{{ $card['name'] }}">{{ ucfirst($card['name']) }}</option>
                @endforeach
            </select>
            <select name="card_money" class="form-control mb-1 mr-1 select_card_money">
                <option value="">Mệnh giá</option>
            </select>
            <select name="type_trade" class="form-control mb-1 mr-1 select_type_trade">
                <option value="">Phương thức gạch thẻ</option>
                <option value="1" data-type="slow">Gạch nhanh: 0% - 0VNĐ</option>
                <option value="2" data-type="fast">Gạch chậm: 0% - 0VNĐ</option>
            </select>
            <input type="text" class="form-control mb-1 mr-1" placeholder="Số serial">
            <input type="text" class="form-control mb-1 mr-1" placeholder="Mã thẻ">
            <button class="btn btn-danger btn-add-area-trade" onclick="RemoveRowTrade(this)">
                <img class="m-0" src="{{ asset('image/icon/trash.svg') }}" alt="plus">
            </button>
        </div>
        <div class="form-group d-flex form-trade-card-home position-relative">
            <select name="card_type" class="form-control mb-1 mr-1 select_card_type">
                <option value="">Loại thẻ</option>
                @foreach($cardList as $key => $card)
                    <option value="{{ $card['name'] }}">{{ ucfirst($card['name']) }}</option>
                @endforeach
            </select>
            <select name="card_money" class="form-control mb-1 mr-1 select_card_money">
                <option value="">Mệnh giá</option>
            </select>
            <select name="type_trade" class="form-control mb-1 mr-1 select_type_trade">
                <option value="">Phương thức gạch thẻ</option>
                <option value="1" data-type="slow">Gạch nhanh: 0% - 0VNĐ</option>
                <option value="2" data-type="fast">Gạch chậm: 0% - 0VNĐ</option>
            </select>
            <input type="text" class="form-control mb-1 mr-1" placeholder="Số serial">
            <input type="text" class="form-control mb-1 mr-1" placeholder="Mã thẻ">
            <button class="btn btn-success btn-add-area-trade" onclick="CopyRowTrade()">
                <img src="{{ asset('image/icon/plus.svg') }}" alt="plus">
                <span>Thêm</span>
            </button>
        </div>
        <div id="row_save_position_add_trade">
            @if(logined())
                <div class="alert alert-warning">
                    <ul style="list-style: decimal; padding-left: 20px">
                        <li>Đối với gạch chậm, thời gian xác minh thẻ tối đa là 5 phút.</li>
                        <li>Sau 5 phút kể từ khi gửi thẻ, nếu hệ thống không xác minh được thẻ thì thẻ sẽ bị đẩy sang gạch nhanh.</li>
                    </ul>
                </div>
            @endif
        </div>
        <div class="btn-trade-card text-center">
            <a class="btn btn-warning" href="{{ route('trade-card') }}">Gạch thẻ</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if(!logined())
                    <div class="alert alert-danger">Vui lòng <a href="{{ route('auth.view') }}" class="text-decoration-none">đăng nhập</a> để sử dụng dịch vụ!</div>
                @endif
                <div class="service_fee_table mt-4">
                    <h4 class="font-weight-bold">Bảng phí đổi thẻ cào</h4>
                    <ul class="nav nav-tabs service_fee_table_tab" role="tablist">
                        @foreach($ratesTable as $card => $rate)
                            <li class="nav-item">
                                <a class="nav-link" id="target_{{ $card }}_tab" data-toggle="tab" href="#target_{{ $card }}" role="tab" aria-controls="home">{{ ucfirst($card) }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content service_fee_table_content">
                        @foreach($ratesTable as $card => $rate)
                            <div class="tab-pane fade" id="target_{{ $card }}" role="tabpanel" aria-labelledby="target_{{ $card }}_tab">
                                <table class="table table-bordered table-responsive-xl text-center">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col" colspan="2">Nhóm thành viên</th>
                                        @foreach($rate as $money => $_r)
                                            <th scope="col">Thẻ {{ number_format($money) }}đ</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!logined() || user()->type_user == 'tongdaily')
                                        <tr>
                                            <th scope="col" colspan="2">Tổng Đại Lý</th>
                                            @foreach($rate as $money => $_r)
                                                <td>{{ $_r['rate_tongdaily'] }}%</td>
                                            @endforeach
                                        </tr>
                                    @endif
                                    @if(!logined() || user()->type_user == 'daily')
                                        <tr>
                                            <th scope="col" colspan="2">Đại Lý</th>
                                            @foreach($rate as $money => $_r)
                                                <td>{{ $_r['rate_daily'] }}%</td>
                                            @endforeach
                                        </tr>
                                    @endif
                                    @if(!logined() || user()->type_user == 'nomal')
                                        <tr>
                                            <th scope="col" rowspan="2">Thành viên</th>
                                            <th scope="col">Gạch nhanh</th>
                                            @foreach($rate as $money => $_r)
                                                <td>{{ $_r['rate_use'] }}%</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th scope="col">Gạch chậm</th>
                                            @foreach($rate as $money => $_r)
                                                <td>{{ $_r['rate_slow'] }}%</td>
                                            @endforeach
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4 mb-4">
            <div class="col-12">
                <h3 class="text-center font-weight-bold">MUA THẺ</h3>
                <p class="mb-2 text-center">Mua thẻ bị <b>từ chối</b> là giao dịch thất bại, hệ thống sẽ tự động hoàn tiền về tài khoản sau vài phút.</p>
                <div class="row-box-card d-flex align-items-center justify-content-center">
                    @foreach(['viettel', 'vinaphone', 'mobifone', 'vietnamobile', 'zing', 'garena'] as $card)
                        <a class="box-card text-decoration-none" href="{{ route('buy-card') }}">
                            <div class="box-car-img text-center">
                                <img src="{{ asset("image/card/$card.png") }}" alt="{{ ucfirst($card) }}">
                            </div>
                            <p class="text-center mb-2 mt-2">Thẻ {{ ucfirst($card) }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        @if(!logined())
        const slideImage = {
            pc: [
                '{{ asset('image/slide_home/bannerslide1.jpg') }}',
                '{{ asset('image/slide_home/bannerslide2.png') }}'
            ],
            mb: [
                '{{ asset('image/slide_home/slide_1_mb.jpg') }}',
                '{{ asset('image/slide_home/slide_2_mb.png') }}'
            ],
            sp: [
                '{{ asset('image/slide_home/slide_1_sp.jpg') }}',
                '{{ asset('image/slide_home/slide_2_sp.png') }}'
            ]
        }

        let listImgSlide;
        if (window.innerWidth > 991) {
            listImgSlide = slideImage.pc;
        } else if (window.innerWidth > 545) {
            listImgSlide = slideImage.mb;
        } else {
            listImgSlide = slideImage.sp;
        }

        const swipper = $('#slide-home .swiper-wrapper');
        swipper.empty();

        for (const img of listImgSlide) {
            const imgEl = $('<img class="w-100" src="' + img + '" alt="No Alt">');
            const divSlide = $('<div />').addClass('swiper-slide');
            divSlide.append(imgEl);
            swipper.append(divSlide);
        }

        const swiper = new Swiper('.swiper', {
            // Optional parameters
            direction: 'horizontal',
            loop: true,
            autoplay: true,
            autoheight: false,

            // If we need pagination
            pagination: {
                el: '.swiper-pagination',
            },

            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
        @endif
    </script>
    <script>
        let rates = {!! json_encode($rates) !!};
        let template = $('[data-template="label-money"]');
        let listNotAuto = {!! json_encode($listNotAuto) !!};

        const resetTradeCard = function (parentBox = null) {
            if (parentBox == null) {
                parentBox = $('.container-trade-card-home');
            }
            parentBox.find('[name="type_trade"]').each(function () {
                $(this).empty();
                $(this).append('<option value="">Phương thức gạch thẻ</option>');
                $(this).append('<option value="1" data-type="slow">Gạch nhanh: 0% - 0VNĐ</option>');
                $(this).append('<option value="2" data-type="fast">Gạch chậm: 0% - 0VNĐ</option>');
            });
        }

        $('.container-trade-card-home').on('change', '[name="card_type"]', function () {
            let val = $(this).val();
            let rate = rates[val];
            let parentBox = $(this).parent();
            let areaMoney = parentBox.find('.select_card_money');

            try {
                resetTradeCard(parentBox);
                if (listNotAuto.indexOf(val) != -1) {
                    parentBox.find('[name="type_trade"] [data-type="fast"]').addClass('d-none');
                    parentBox.find('[name="type_trade"] [data-type="slow"]').prop('selected', true);
                } else {
                    parentBox.find('[name="type_trade"] [data-type="fast"]').removeClass('d-none');
                }
            } catch (e) {
                console.log(e)
            }

            areaMoney.empty();
            areaMoney.append('<option value="">Mệnh giá</option>');

            if (rate == undefined) {
                console.log(type, rate)
                return;
            }

            $.each(rate, function (index, r) {
                let money = r.price;
                let tempEl = $("<option />");
                let id = 'money-' + money;
                tempEl.text(App.setPriceFormat(money));
                tempEl.attr('data-id', id)
                    .attr('data-value', money)
                    .attr('data-rate', r.rate_use)
                    .attr('data-rate-slow', r.rate_slow);
                areaMoney.append(tempEl);
            });
        });

        $('.container-trade-card-home').on('change', '[name="card_money"]', function () {
            const parentBox = $(this).parent();
            const optionEl = $(this).find('option:selected');
            const rate = parseFloat(optionEl.attr('data-rate'));
            const rateSlow = parseFloat(optionEl.attr('data-rate-slow'));
            const money = parseInt(optionEl.attr('data-value'));
            const moneySlow = App.setPriceFormat(money - (money * rateSlow / 100));
            const moneyFast = App.setPriceFormat(money - (money * rate / 100));

            resetTradeCard(parentBox);
            parentBox.find('[data-type="slow"]').text('Gạch nhanh: ' + rate + '% - ' + moneyFast + 'VNĐ');
            parentBox.find('[data-type="fast"]').text('Gạch chậm: ' + rateSlow + '% - ' + moneySlow + 'VNĐ');
        });
    </script>
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            $('.service_fee_table_tab').find('.nav-item:first-child .nav-link').trigger('click');
        });
        window.CopyRowTrade = function () {
            const template = $('[data-id="template"]').clone();
            template.removeAttr('data-id');
            template.addClass('d-flex').removeClass('d-none');
            $("#row_save_position_add_trade").before(template);
        }
        window.RemoveRowTrade = function (el) {
            $(el).closest('.form-trade-card-home').remove();
        }
    </script>
@endsection
