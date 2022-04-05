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

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ config('meta.title') }}">
    <meta property="og:type" content="{{ config('meta.type') }}">
    <meta property="og:url" content="{{ url('') }}"/>
    <meta property="og:image" content="{{ asset('image/logo-trans.png') }}">
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
            <div class="col-2">
                <a href="{{ url('') }}" class="d-block">
                    <img src="{{ asset('image/logo.png') }}" alt="Logo" class="header-logo">
                </a>
            </div>
            <div class="col-8">
                <div class="header-menu d-none d-lg-block">
                    <ul>
                        <li data-id="menu-trade-card"><a href="{{ route('trade-card') }}">Đổi thẻ cào</a></li>
                        <li data-id="menu-buy-card"><a href="{{ route('buy-card') }}">Mua thẻ cào</a></li>
                        <li data-id="menu-recharge"><a href="{{ route('recharge') }}">Nạp tiền</a></li>
                        <li data-id="menu-withdraw"><a href="{{ route('withdraw') }}">Rút tiền</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-2 text-center">
                <div class="header-auth">
                    @if(!logined())
                        <a class="btn btn-success" href="{{ route('auth.view') }}">Đăng nhập</a>
                    @else
                        <div class="fas fa-bars bar-user-menu bar-user-icon">
                            <div class="menu-user" style="display: none">
                                <p class="text-center">{{ user()->fullname }}</p>
                                <ul>
                                    <li><a href="{{ route('profile.home') }}">Thông tin cá nhân</a></li>
                                    <li><a href="{{ route('bank.list') }}">Thẻ ngân hàng</a></li>
                                    <hr />
                                    <div class="d-lg-none">
                                        <li data-id="menu-trade-card"><a href="{{ route('trade-card') }}">Đổi thẻ cào</a></li>
                                        <li data-id="menu-buy-card"><a href="{{ route('buy-card') }}">Mua thẻ cào</a></li>
                                        <li data-id="menu-recharge"><a href="{{ route('recharge') }}">Nạp tiền</a></li>
                                        <li data-id="menu-withdraw"><a href="{{ route('withdraw') }}">Rút tiền</a></li>
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

<div id="copyright">
    <hr class="m-0">
    <p class="m-0 p-3 text-center">Bản quyền &copy; {{ request()->getHost() }} {{ date('Y') }}</p>
</div>

<script src="{{ asset('vendor/alertify/alertify.js') }}"></script>
<script src="{{ asset('js/autosize.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

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
        $('[data-id="{{ session()->pull('menu-active') }}"]').addClass('active');
    @endif

    //remove elm script
    document.querySelector('#script_save_error').remove();
</script>

@yield('script')

</body>
</html>
