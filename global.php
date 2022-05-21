<?php

use App\Models\Notification;
use App\Models\RateCard;
use App\Models\RateCardSell;
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
            'buy' => 'bán thẻ cào',
            'trade' => 'thu thẻ cào',
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

function getTextReport(): array
{
    return [
        'all' => 'Tất cả',
        'success' => 'Thành công',
        'error' => 'Thất bại / Hủy bỏ',
        'pending' => 'Đang xử lý',
        'to_fast' => 'Chuyển sang nhanh',
        'money' => 'Tổng tiền',
        'money_after_rate' => 'Tổng tiền sau chiết khấu',
        'error_money' => 'Sai mệnh giá'
    ];
}

function convert_vi_to_en($str): array|string|null
{
    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
    $str = preg_replace("/(đ)/", "d", $str);
    $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "A", $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "E", $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", "I", $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "O", $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "U", $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "Y", $str);
    return preg_replace("/(Đ)/", "D", $str);
}

function notification(): string
{
    return Notification::buildNotificationShow();
}

function getNumberTurnTransfer($transferOnDay = 0): int
{
    return (int)$transferOnDay - user()->count_number_trasnfer;
}

function isIgnoreBg(): bool
{
    $aryUriIgnoreBg = [
        '/',
        'recharge',
        'transfer',
        'buy-card',
        'trade-card',
    ];

    return request()->is(...$aryUriIgnoreBg);
}

function getRates (){
    return RateCard::getRate();
}

function getListCardTrade($rates = null){
    $rates = $rates == null ? RateCard::getListCardTrade() : $rates;
    return array_reduce($rates, function ($result, $card) {
        $card = end($card);
        $result[$card['name']] = [
            'name' => $card['name']
        ];
        return $result;
    }, []);
}
