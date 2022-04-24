<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="content-language" content="{{ app()->getLocale() }}">
    <meta name="robots" content="index, follow'">
    <meta name="description" content="{{ config('meta.description') }}">
    <meta name="keywords" content="{{ config('meta.keywork') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ config('meta.title') }}">
    <meta property="og:type" content="{{ config('meta.type') }}">
    <meta property="og:url" content="{{ url('') }}"/>
    <meta property="og:image" content="{{ asset('image/logo-trans.png') }}">
    <meta property="og:image:alt" content="{{ config('meta.name') }}">
    <meta property="og:description" content="{{ config('meta.description') }}">
    <meta property="og:site_name" content="{{ config('meta.name') }}">
    <meta property="article:section" content="{{ config('meta.description') }}">
    <meta property="article:tag" content="{{ config('meta.keywork') }}">

    <title>{{ env('APP_FULL_NAME', 'Laravel') }}</title>

    <link rel="shortcut icon" href="{{ asset('image/logo-trans.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/bs4/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/all.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/alertify/css/alertify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/alertify/css/themes/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('style')

    <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('vendor/bs4/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/bs4/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/swiper/swiper.min.js') }}"></script>
    <script src="{{ asset('js/request.js') }}"></script>
</head>
<body class="custom-scrollbar">

<div id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-6 col-lg-2">
                <a href="{{ url('') }}" class="d-block">
                    <img src="{{ asset('image/logo.png') }}" alt="Logo" class="header-logo">
                </a>
            </div>
            <div class="d-none d-lg-block col-lg-8">
                <div class="header-menu d-none {{ logined() ? 'd-lg-block' : '' }}">
                    <ul>
                        <li>
                            <div class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Thẻ cào</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('buy-card') }}" data-id="menu-buy-card">Mua thẻ</a>
                                    <a class="dropdown-item" href="{{ route('trade-card') }}" data-id="menu-trade-card">Đổi thẻ</a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Dịch vụ</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('recharge') }}" data-id="menu-recharge">Nạp tiền</a>
                                    <a class="dropdown-item" href="{{ route('withdraw') }}" data-id="menu-withdraw">Rút tiền</a>
                                    <a class="dropdown-item" href="{{ route('pay-bill') }}" data-id="menu-pay-bill">Thanh toán cước</a>
                                </div>
                            </div>
                        </li>
                        <li data-id="menu-discount">
                            <a href="{{ route('chiet-khau') }}">Chiết khấu</a>
                        </li>
                        <li data-id="connect-api">
                            <a href="{{ route('connect-api') }}">Kết nối API</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-6 col-lg-2 text-center">
                <div class="header-auth">
                    @if(!logined())
                        <a class="btn btn-success" href="{{ route('auth.view') }}">Đăng nhập</a>
                    @else
                        <div class="fas fa-bars bar-user-menu bar-user-icon">
                            <div class="menu-user cursor-nomal" style="display: none">
                                <p class="text-center">{{ user()->fullname }}</p>
                                <p class="text-center font-weight-normal">{{ number_format(user()->money) }} đ</p>
                                <hr />
                                <ul>
                                    @if(is_admin())
                                        <li><a href="{{ route('admin.home') }}">Admin Panel</a></li>
                                        <hr />
                                    @endif
                                    <li><a href="{{ route('profile.home') }}">Thông tin cá nhân</a></li>
                                    <li><a href="{{ route('bank.list') }}">Thẻ ngân hàng</a></li>
                                    <hr />
                                    <div class="d-lg-none">
                                        <li data-id="menu-trade-card"><a href="{{ route('trade-card') }}">Đổi thẻ cào</a></li>
                                        <li data-id="menu-buy-card"><a href="{{ route('buy-card') }}">Mua thẻ cào</a></li>
                                        <li data-id="menu-recharge"><a href="{{ route('recharge') }}">Nạp tiền</a></li>
                                        <li data-id="menu-withdraw"><a href="{{ route('withdraw') }}">Rút tiền</a></li>
                                        <li data-id="menu-discount"><a href="{{ route('chiet-khau') }}">Chiết khấu</a></li>
                                        <li data-id="connect-api"><a href="{{ route('connect-api') }}">Kết nối API</a></li>
                                        <hr />
                                    </div>
                                    <li><a href="{{ route('auth.logout') }}">Đăng xuất</a></li>
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div id="main-contents">
    @yield('contents')
</div>

@if(isset($show_menu_bottom) && $show_menu_bottom === true)
    <div id="bottom_menu" class="container-fluid d-flex">
        <a class="text-decoration-none" href="{{ route('trade-card') }}">
            <div style="--img-icon-url: url('{{ asset('image/icon/icon1.svg') }}')" class="img-icon"></div>
            <p class="w-100">Đổi thẻ cào</p>
        </a>
        <a class="text-decoration-none" href="{{ route('buy-card') }}">
            <div style="--img-icon-url: url('{{ asset('image/icon/icon2.svg') }}')" class="img-icon"></div>
            <p class="w-100">Mua thẻ cào</p>
        </a>
        <a class="text-decoration-none" href="{{ route('recharge') }}">
            <div style="--img-icon-url: url('{{ asset('image/icon/icon1.svg') }}')" class="img-icon"></div>
            <p class="w-100">Nạp tiền</p>
        </a>
        <a class="text-decoration-none" href="{{ route('withdraw') }}">
            <div style="--img-icon-url: url('{{ asset('image/icon/icon3.svg') }}')" class="img-icon"></div>
            <p class="w-100">Rút tiền</p>
        </a>
        <a class="text-decoration-none" href="{{ route('chiet-khau') }}">
            <div style="--img-icon-url: url('{{ asset('image/icon/icon4.svg') }}')" class="img-icon"></div>
            <p class="w-100">Chiết khấu</p>
        </a>
        <a class="text-decoration-none" href="{{ route('pay-bill') }}">
            <div style="--img-icon-url: url('{{ asset('image/icon/icon3.svg') }}')" class="img-icon"></div>
            <p class="w-100">Thanh toán cước</p>
        </a>
        <a class="text-decoration-none" href="{{ route('connect-api') }}">
            <div style="--img-icon-url: url('{{ asset('image/icon/icon3.svg') }}')" class="img-icon"></div>
            <p class="w-100">Kết nối API</p>
        </a>
    </div>
@endif
<div id="copyright">
    <hr class="m-0">
    <p class="m-0 p-3 text-center">Bản quyền &copy; {{ request()->getHost() }} {{ date('Y') }}</p>
</div>

<script src="{{ asset('vendor/alertify/alertify.js') }}"></script>
<script src="{{ asset('js/autosize.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<script id="script_save_error">
    //assign errors
    window.errors = {!! json_encode($errors->toArray()) !!};

    //show notif
    @if(session()->has('notif'))
        alertify.alert('Notification', '{{session()->pull('notif')}}');
    @endif

    //show error
    @if(session()->has('mgs_error'))
        alertify.alert('Error', '{{session()->pull('mgs_error')}}');
        $('.alertify .ajs-header').addClass('alert-danger');
    @endif
    //active menu
    @if(session()->has('menu-active'))
        let activeElement = $('[data-id="{{ session()->pull('menu-active') }}"]');
        if(activeElement.length > 0) {
            activeElement.addClass('active');
            if(activeElement.hasClass('dropdown-item')) {
                activeElement.closest('li').addClass('active');
            }
        }
    @endif

    //remove elm script
    document.querySelector('#script_save_error').remove();
</script>

@yield('script')

</body>
</html>
