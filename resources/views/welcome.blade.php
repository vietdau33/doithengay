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
    @include('template.trade_box')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if(!logined())
                    <div class="alert alert-danger">Vui lòng <a href="{{ route('auth.view') }}" class="text-decoration-none">đăng nhập</a> để sử dụng dịch vụ!</div>
                @endif
                @include('template.table_fee')
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
@endsection
