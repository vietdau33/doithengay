<?php

return [
    'list' => [
        'viettel' => [
            'name' => 'Viettel',
            'image' => 'image/card/viettel.png',
            'alias' => 'Viettel'
        ],
        'mobifone' => [
            'name' => 'Mobifone',
            'image' => 'image/card/mobi.png',
            'alias' => 'Mobifone'
        ],
        'vinaphone' => [
            'name' => 'Vinaphone',
            'image' => 'image/card/vina.png',
            'alias' => 'Vinaphone'
        ],
        'vietnamobile' => [
            'name' => 'Vietnamobile',
            'image' => 'image/card/vnm.png',
            'alias' => 'Vietnamobile'
        ],
        'vcoin' => [
            'name' => 'Vcoin',
            'image' => 'image/card/vcoin.jpg',
            'alias' => 'Vcoin'
        ],
        'zing' => [
            'name' => 'Zing',
            'image' => 'image/card/zing.jpg',
            'alias' => 'Zing'
        ],
        'gate' => [
            'name' => 'Gate',
            'image' => 'image/card/gate.jpg',
            'alias' => 'Gate'
        ],
        'garena' => [
            'name' => 'Garena',
            'image' => 'image/card/garena.jpg',
            'alias' => 'Garena'
        ]
    ],
    'api' => [
        'trade' => 'http://api.autocard365.com/api/card',
        'check-trade' => 'https://api.autocard365.com/api/checktask/{taskid}',
        'rate' => 'https://api.autocard365.com/api/cardrate?apikey={apikey}',
        'buy' => 'https://api.autocard365.com/api/buycard'
    ],
    'api_cardvip' => [
        'authen' => 'https://services.cardvip.vn/api/oauth/token',
        'buy' => 'https://services.cardvip/vn/api/order/buycard',
        'rate' => 'https://services.cardvip.vn/api/card/discount'
    ]
];
