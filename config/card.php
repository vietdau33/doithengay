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
        ],
        'gosu' => [
            'name' => 'Gosu',
            'image' => 'image/card/gosu.png',
            'alias' => 'Gosu'
        ],
        'funcard' => [
            'name' => 'Funcard',
            'image' => 'image/card/funcard.png',
            'alias' => 'Funcard'
        ]
    ],
    'trade' => [
        'viettel' => [
            'id' => 1,
            'name' => 'Viettel',
            'image' => 'image/card/viettel.png'
        ],
        'mobifone' => [
            'id' => 2,
            'name' => 'Mobifone',
            'image' => 'image/card/mobi.png'
        ],
        'vinaphone' => [
            'id' => 3,
            'name' => 'Vinaphone',
            'image' => 'image/card/vina.png'
        ],
        'vietnamobile' => [
            'id' => 16,
            'name' => 'Vietnamobile',
            'image' => 'image/card/vnm.png'
        ],
        'zing' => [
            'id' => 14,
            'name' => 'Zing',
            'image' => 'image/card/zing.jpg'
        ],
        'gate' => [
            'id' => 15,
            'name' => 'Gate',
            'image' => 'image/card/gate.jpg'
        ]
    ],
    'api' => [
        'trade' => 'http://api.autocard365.com/api/card',
        'check-trade' => 'https://api.autocard365.com/api/checktask/{taskid}',
        'rate' => 'https://api.autocard365.com/api/cardrate?apikey={apikey}',
        'buy' => 'https://api.autocard365.com/api/buycard'
    ],
    'rate-compare' => 10
];
