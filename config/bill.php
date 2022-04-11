<?php

$vendor_list = [
    'viettel' => [
        'name' => 'Viettel',
        'image' => 'image/card/viettel.png',
        'alias' => 'viettel'
    ],
    'mobifone' => [
        'name' => 'Mobifone',
        'image' => 'image/card/mobifone.png',
        'alias' => 'mobifone'
    ],
    'vinaphone' => [
        'name' => 'Vinaphone',
        'image' => 'image/card/vinaphone.png',
        'alias' => 'vinaphone'
    ],
];

return [
    'main_account' => [
        'image' => '/image/phone-pay.svg',
        'text' => 'Nạp tài khoản chính',
        'vendor' => $vendor_list
    ],
    'prepaid_mobile' => [
        'image' => '/image/phone-pay.svg',
        'text' => 'Di động trả trước',
        'vendor' => $vendor_list
    ],
    'postpaid_mobile' => [
        'image' => '/image/phone-pay.svg',
        'text' => 'Di động trả sau',
        'vendor' => $vendor_list
    ],
    'adls' => [
        'image' => '/image/adls.svg',
        'text' => 'FTTH/ADSL VIETTEL',
        'vendor' => [
            'viettel' => [
                'name' => 'Viettel',
                'image' => 'image/card/viettel.png',
                'alias' => 'Viettel'
            ],
        ]
    ],
    'k_plus' => [
        'image' => '/image/k_plus.svg',
        'text' => 'K +',
        'vendor' => [
            'k_plus' => [
                'name' => 'K+',
                'image' => 'image/card/k_plus.png',
                'alias' => 'k_plus'
            ],
        ]
    ],
];
