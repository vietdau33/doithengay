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
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('style')

    <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('vendor/bs4/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/bs4/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/swiper/swiper.min.js') }}"></script>
    <script src="{{ asset('js/request.js') }}"></script>
</head>
<body>

<div id="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-2">
                <a href="{{ url('') }}" class="d-block">
                    <img src="{{ asset('image/logo.png') }}" alt="Logo" class="header-logo">
                </a>
            </div>
            <div class="col-8">
                <div class="header-menu">
                    <ul>
                        <li><a href="#">Đổi thẻ cào</a></li>
                        <li><a href="#">Mua thẻ cào</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-2 text-center">
                <div class="header-auth">
                    <a class="btn btn-success" href="{{ route('auth.view') }}">Đăng nhập</a>
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
    <p class="m-3 text-center">Bản quyền &copy; {{ request()->getHost() }} {{ date('Y') }}</p>
</div>

<script src="{{ asset('js/app.js') }}"></script>
@yield('script')
</body>
</html>
