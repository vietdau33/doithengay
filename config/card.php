<?php

return [
    'list' => [
        'viettel' => [
            'name' => 'Viettel',
            'image' => 'image/card/viettel.png',
            'alias' => 'viettel'
        ],
        'mobifone' => [
            'name' => 'Mobifone',
            'image' => 'image/card/mobi.png',
            'alias' => 'mobifone'
        ],
        'vinaphone' => [
            'name' => 'Vinaphone',
            'image' => 'image/card/vina.png',
            'alias' => 'vinaphone'
        ],
        'vietnamobile' => [
            'name' => 'Vietnamobile',
            'image' => 'image/card/vnm.png',
            'alias' => 'vietnamobile'
        ],
        'vcoin' => [
            'name' => 'Vcoin',
            'image' => 'image/card/vcoin.jpg',
            'alias' => 'vcoin'
        ],
        'zing' => [
            'name' => 'Zing',
            'image' => 'image/card/zing.jpg',
            'alias' => 'zing'
        ],
        'gate' => [
            'name' => 'Gate',
            'image' => 'image/card/gate.jpg',
            'alias' => 'gate'
        ],
        'garena' => [
            'name' => 'Garena',
            'image' => 'image/card/garena.jpg',
            'alias' => 'garena'
        ]
    ],
    'api' => [
        'trade' => 'http://api.autocard365.com/api/card',
        'check-trade' => 'https://api.autocard365.com/api/checktask/{taskid}',
        'rate' => 'https://api.autocard365.com/api/cardrate?apikey={apikey}',
        'buy' => 'https://api.autocard365.com/api/buycard'
    ]
];
