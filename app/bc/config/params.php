<?php

return [
    'adminEmail' => 'admin@example.com',
    'apiAddress' => 'http://api-bpt.ooo.ua/api/',
//    'apiAddress' => 'http://192.168.3.11:3000/api/',
//    'apiAddress' => 'http://172.17.0.27:3000/api/',
    'defaultCountry' => 'RU',
    'useCache' => true,
    'certificate' => [
        'name' => [
            'height'   => 1600,
            'width'    => 725,
            'font'     => 'fonts/play.ttf',
            'font_size' => 70
        ],
        'id' => [
            'height'   => 2300,
            'width'    => 1725,
            'font'     => 'fonts/play.ttf',
            'font_size' => 50
        ],
        'date' => [
            'height'   => 2480,
            'width'    => 1725,
            'font'     => 'fonts/play.ttf',
            'font_size' => 50
        ]
    ],
    'recommenderSearchUrl' => '//www.google.com/search?q=%D0%BF%D0%B0%D1%80%D1%82%D0%BD%D0%B5%D1%80+Business+Process+Technologies&oq=%D0%BF%D0%B0%D1%80%D1%82%D0%BD%D0%B5%D1%80+Business+Process+Technologies&sourceid=chrome&ie=UTF-8',
    'scheme' => 'https',
    'secretKey' => 'gdaksgfxcbgfas3456734665asgdfhkasgdh',
    'simpleLoginKey' => 'bO6PXtsa8B',
    'branch' => [
        'base_url' => 'https://api.branch.io/v1/url',
        'base_url_app' => 'https://api.branch.io/v1/app/',
        'branch_key' => 'key_live_ppwANFl3plGOSUW1pTE6WkigvCf6c2iT',
        'branch_secret' => 'secret_live_zSlR4aCZPL0WD6VfSM3ahugGl1d31h8I',
        'IOS_PATH' => 'https://www.apple.com/itunes/',
        'ANDROID_PATH' => 'https://play.google.com/store/apps/details?id=com.vipvip',
        'alias' => false
    ]
];
