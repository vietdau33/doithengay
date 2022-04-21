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

if (!function_exists('getNameBank')) {
    function getNameBank($type, $name): string
    {
        $conf = config('withdraw');
        return $conf[$type][$name] ?? '';
    }
}

if (!function_exists('getTypeBank')) {
    function getTypeBank($type): string
    {
        return match ($type) {
            'wallet' => 'Ví điện tử',
            'bank' => 'Thẻ ngân hàng',
            default => 'Error!',
        };
    }
}

if (!function_exists('get_card_trade')) {
    function get_card_trade($trade, $rates, $listId): string
    {
        $rates = $rates[$listId[$trade['card_type']]];
        $rate = $rates[$trade['card_money']];
        return ucfirst($rate['name']);
    }
}

if (!function_exists('get_title_setting_status')) {
    function get_title_setting_status($type): string
    {
        return match ($type) {
            'buy' => 'mua thẻ cào',
            'trade' => 'bán thẻ cào',
            'bill' => 'gạch cước',
            default => '',
        };
    }
}

if (!function_exists('get_text_type_account_bill')) {
    function get_text_type_account_bill($type): string
    {
        return match ($type) {
            'viettel' => 'Viettel',
            'mobifone' => 'Mobifone',
            'vinaphone' => 'Vinaphone',
            'k_plus' => 'K+',
            default => '',
        };
    }
}

if (!function_exists('time_elapsed_string')) {
    /**
     * @throws Exception
     */
    function time_elapsed_string($datetime, $full = false): string
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'năm',
            'm' => 'tháng',
            'w' => 'tuần',
            'd' => 'ngày',
            'h' => 'giờ',
            'i' => 'phút',
            's' => 'giây',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v;
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' trước' : 'Vừa tạo';
    }
}
