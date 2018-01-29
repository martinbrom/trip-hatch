<?php

return [
    '401' => [
        'message' => 'Na tuto akci nemáte dostatečná práva',
        'heading' => 'Hmm, tady jste asi skončit nechtěli',
        'link' => [
            'text' => 'Můžete se zkusit vrátit a obnovit stránku nebo navštívit naši',
            'content' => 'domovskou stránku'
        ],
        'title' => 'Trip Hatch - nepřístupno'
    ],
    '404' => [
        'message' => ' ',
        'heading' => 'Stránka, kterou hledáte, neexistuje',
        'link' => [
            'text' => 'Můžete se zkusit vrátit nebo navštívit naši',
            'content' => 'domovskou stránku'
        ],
        'title' => 'Trip Hatch - stránka nenalezena'
    ],
    '500' => [
        'message' => 'Náš server se momentálně potýká s nějakými problémy, ale už pracujeme na opravě',
        'heading' => 'Ups, zřejmě jsme něco rozbili',
        'link' => [
            'text' => 'Prosíme chvíli počkejte nebo zkuste kontaktovat našeho',
            'content' => 'administrátora'
        ],
        'title' => 'Trip Hatch - interní chyba serveru'
    ],
    'base' => [
        'message' => ' ',
        'heading' => 'Jejda! Něco se pokazilo',
        'link' => [
            'text' => 'Můžete se zkusit vrátit nebo navštívit naši',
            'content' => 'domovskou stránku'
        ],
        'title' => 'Trip Hatch - něco se pokazilo'
    ]
];
