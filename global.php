<?php

use Illuminate\Contracts\Auth\Authenticatable;

if (!function_exists('logined')) {
    function logined(): bool
    {
        return auth()->check();
    }
}

if (!function_exists('user')) {
    function user(): Authenticatable
    {
        if (!logined()) {
            redirect()->to('/login');
            exit(1);
        }
        return auth()->user();
    }
}

if (!function_exists('is_user')) {
    function is_user(): bool
    {
        return logined() && user()->role == 'user';
    }
}

if (!function_exists('is_admin')) {
    function is_admin(): bool
    {
        return logined() && user()->role == 'admin';
    }
}


