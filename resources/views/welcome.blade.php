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

                <!-- If we need navigation buttons -->
               <!-- <div class="swiper-button-prev"></div>
                 <div class="swiper-button-next"></div> -->
            </div>
        </div>
    @endif
    <div class="container mt-4">
        <h2 class="text-center font-weight-bold">Đổi thẻ cào!</h2>
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
                <span class="font-weight-bold">Hướng dẫn tích hợp API gạch thẻ tự động cho Shop <a href="">tại đây</a>.</span>
            </li>
            <li>
                <img src="{{ asset('image/arrow-1.gif') }}" alt="Arrow">
                <span class="font-weight-bold">Không nhận API game bài, cổng game bài cố tình đấu api sẽ bị khóa.</span>
            </li>
        </ul>
    </div>
@endsection
@section('script')
    <script>
        @if(!logined())
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
