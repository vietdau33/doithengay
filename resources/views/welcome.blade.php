@extends('layout')
@section('contents')
    <div id="slide-home" class="container-fluid pt-3 pb-3">
        <div class="swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img class="w-100" src="{{ asset('image/slide_home/1.jpg') }}" alt="1">
                </div>
                <div class="swiper-slide">
                    <img class="w-100" src="{{ asset('image/slide_home/2.jpg') }}" alt="2">
                </div>
            </div>
            <!-- If we need pagination -->
            <div class="swiper-pagination"></div>

            <!-- If we need navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>

    <div class="container">
        <h2 class="text-center font-weight-bold">Đổi thẻ ngay !</h2>
        <ul class="list-notif mb-4">
            <li>
                <img src="{{ asset('image/arrow-1.gif') }}" alt="Arrow">
                <span class="font-weight-bold">Không nhận thẻ trộm cắp, lừa đảo. Phát hiện khóa tài khoản vĩnh viễn không cần báo trước.</span>
            </li>
            <li>
                <img src="{{ asset('image/arrow-1.gif') }}" alt="Arrow">
                <span class="font-weight-bold">Hỗ trợ nạp / đổi nhiều loại thẻ: Viettel, Vina, Mobi, Vnmb, Gate, Zing, Vcoin, Garena. Xử lý tự động 100%</span>
            </li>
            <li>
                <img src="{{ asset('image/arrow-1.gif') }}" alt="Arrow">
                <span class="font-weight-bold">Rút tiền về ATM/ Ví điện tử trong 30 phút</span>
            </li>
        </ul>
    </div>
@endsection
@section('script')
    <script>
        const swiper = new Swiper('.swiper', {
            // Optional parameters
            direction: 'horizontal',
            loop: true,
            autoplay: true,
            autoheight: true,

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
    </script>
@endsection
