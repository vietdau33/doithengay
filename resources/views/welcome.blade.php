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
                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    @endif
    <div class="container mt-4 mb-4">
        <h3 class="text-center font-weight-bold">Đổi thẻ cào!</h3>
        <ul class="list-notif mb-4">
            <li>
                <img src="{{ asset('image/arrow-1.gif') }}" alt="Arrow">
                <span
                    class="font-weight-bold">Viettel cần điền đúng cả seri, điền sai seri khi lỗi sẽ bị xử lý chậm.</span>
            </li>
            <li>
                <img src="{{ asset('image/arrow-1.gif') }}" alt="Arrow">
                <span class="font-weight-bold">Sai mệnh giá 50%.</span>
            </li>
            <li>
                <img src="{{ asset('image/arrow-1.gif') }}" alt="Arrow">
                <span class="font-weight-bold">Hướng dẫn tích hợp API gạch thẻ tự động cho Shop <a href="{{ route('connect-api') }}">tại đây</a>.</span>
            </li>
            <li>
                <img src="{{ asset('image/arrow-1.gif') }}" alt="Arrow">
                <span class="font-weight-bold">Không nhận API game bài, cổng game bài cố tình đấu api sẽ bị khóa.</span>
            </li>
        </ul>
    </div>
    <div class="container-fluid mb-3">
        <div class="form-group d-flex form-trade-card-home">
            <select name="" id="" class="form-control mb-1 mr-1">
                <option value="">Loại thẻ</option>
                <option value="1">Viettel</option>
                <option value="2">Vina</option>
                <option value="3">Mobi</option>
            </select>
            <select name="" id="" class="form-control mb-1 mr-1">
                <option value="">Mệnh giá</option>
                <option value="1">10.000</option>
                <option value="2">20.000</option>
                <option value="3">30.000</option>
            </select>
            <select name="" id="" class="form-control mb-1 mr-1">
                <option value="">Phương thức gạch thẻ</option>
                <option value="1">Gạch chậm: 0% - 0 VNĐ</option>
                <option value="2">Gạch nhanh: 0% - 0 VNĐ</option>
            </select>
            <input type="text" class="form-control mb-1 mr-1" placeholder="Số serial">
            <input type="text" class="form-control mb-1 mr-1" placeholder="Mã thẻ">
        </div>
        <div class="btn-trade-card text-center">
            <button class="btn btn-warning">Gạch thẻ</button>
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
                        @foreach($rates as $card => $rate)
                            <li class="nav-item">
                                <a class="nav-link" id="target_{{ $card }}_tab" data-toggle="tab" href="#target_{{ $card }}" role="tab" aria-controls="home">{{ ucfirst($card) }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content service_fee_table_content">
                        @foreach($rates as $card => $rate)
                            <div class="tab-pane fade" id="target_{{ $card }}" role="tabpanel" aria-labelledby="target_{{ $card }}_tab">
                                <table class="table table-bordered table-responsive-xl">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col" colspan="2">Nhóm thành viên</th>
                                        @foreach($rate as $money => $_r)
                                            <th scope="col">Thẻ {{ number_format($money) }}đ</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th scope="col" colspan="2">Tổng Đại Lý</th>
                                        @foreach($rate as $money => $_r)
                                            <td>{{ $_r['rate_tongdaily'] }}%</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th scope="col" colspan="2">Đại Lý</th>
                                        @foreach($rate as $money => $_r)
                                            <td>{{ $_r['rate_daily'] }}%</td>
                                        @endforeach
                                    </tr>
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
                <h3 class="text-center font-weight-bold">Mua mã thẻ</h3>
                <p class="mb-2 text-center">Mua thẻ bị <b>Chờ xử lý</b> là giao dịch thất bại, hệ thống sẽ tự động hoàn tiền về tài khoản sau vài giờ</p>
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
        if(window.innerWidth > 991) {
            listImgSlide = slideImage.pc;
        }else if(window.innerWidth > 545) {
            listImgSlide = slideImage.mb;
        }else {
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
        window.addEventListener('DOMContentLoaded', function(){
            $('.service_fee_table_tab').find('.nav-item:first-child .nav-link').trigger('click');
        });
    </script>
@endsection
