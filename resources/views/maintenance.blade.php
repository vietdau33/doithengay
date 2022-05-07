<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="content-language" content="{{ app()->getLocale() }}">
    <meta name="robots" content="index, follow'">
    <meta name="description" content="{{ config('meta.description') }}">
    <meta name="keywords" content="{{ config('meta.keywork') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ config('meta.title') }}">
    <meta property="og:type" content="{{ config('meta.type') }}">
    <meta property="og:url" content="{{ url('') }}"/>
    <meta property="og:image" content="{{ asset('image/logo.png') }}">
    <meta property="og:image:alt" content="{{ config('meta.name') }}">
    <meta property="og:description" content="{{ config('meta.description') }}">
    <meta property="og:site_name" content="{{ config('meta.name') }}">
    <meta property="article:section" content="{{ config('meta.description') }}">
    <meta property="article:tag" content="{{ config('meta.keywork') }}">

    <title>{{ env('APP_FULL_NAME', 'Laravel') }}</title>

    <link rel="shortcut icon" href="{{ asset('image/logo.png') }}">
    <style>
        html:after {
            content: "";
            background: #fff url(https://i.imgur.com/TQ1MlZP.jpg) no-repeat center;
            background-size: auto;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 100000;
        }
        body {
            display: none;
        }
        @media screen and (max-width: 800px) {
            html:after {
                background-size: contain;
            }
        }
    </style>
</head>
<body>
</body>
</html>
