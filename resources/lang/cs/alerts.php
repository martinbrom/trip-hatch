<?php

return [
    'login' => [
        'success' => 'Byli jste úspěšně přihlášeni',
        'wrong'   => 'Tuto kombinaci emailu a hesla se v naší databázi nenachází'
    ],
    'logout' => [
        'success' => 'Byli jste úspěšně odhlášeni'
    ],
    'publish' => [
        'success' => 'Výlet byl úspěšně publikován. Veřejnou adresu můžete nalézt pod záložkou "pozvat"',
        'error' => 'Něco se pokazilo během publikování výletu'
    ],
    'classify' => [
        'success' => 'Výlet byl úspěšně skryt',
        'error' => 'Něco se pokazilo během skrývání výletu'
    ],
    'change-display-name' => [
        'success' => 'Vaše veřejné jméno bylo úspěšně změněno',
        'error' => 'Něco se pokazilo během měnění veřejného jména'
    ],
    'change-password' => [
        'success' => 'Vaše heslo bylo úspěšně změněno',
        'error' => 'Něco se pokazilo během měnění hesla'
    ],
    'register' => [
        'success' => 'Byli jste úspěšně registrování',
        'error' => 'Něco se pokazilo během registrování nového účtu'
    ],
    'trip' => [
        'missing' => 'Tento výlet neexistuje',
        'no-travellers' => 'Momentálně nejsou na výletě žádní výletnící',
        'no-organisers' => 'Momentálně nejsou na výletě žádní organizátoři',
        'no-days' => 'Momentálně nejsou na výletě žádné plánované dny'
    ],
    'remove-user' => [
        'error' => 'Něco se pokazilo během odstraňování uživatele z výletu',
        'success' => 'Úspěšně jste odstranili uživatele z výletu',
        'wrong-role' => 'Z výletu můžete odebrat pouze výletníky, ne organizátory'
    ],
    'trip-create' => [
        'success' => 'Nový výlet byl úspěšně vytvořen',
        'error' => 'Něco se pokazilo během vytváření nového výletu'
    ],
    'trip-add-day' => [
        'error' => 'Něco se pokazilo během přidávání nového dne',
        'success' => 'Nový den byl úspěšně přidán'
    ],
    'day' => [
        'missing' => 'Tento den neexistuje'
    ],
    'day-edit' => [
        'error' => 'Něco se pokazilo během upravování dne',
        'success' => 'Den byl úspěšně upraven'
    ],
    'day-delete' => [
        'error' => 'Něco se pokazilo během odstraňování dne',
        'success' => 'Den \':p1\' byl úspěšně odstraněn'
    ],
    'actions' => [
        'success' => 'Akce byly úspěšně načteny'
    ],
    'trip-add-action' => [
        'success' => 'Nová akce byla úspěšně přidána',
        'error' => 'Něco se pokazilo během přidávání nové akce'
    ],
    'action' => [
        'missing' => 'Tato akce neexistuje'
    ],
    'trip-edit-action' => [
        'error' => 'Něco se pokazilo během upravování akce',
        'success' => 'Akce byla úspěšně upravena'
    ],
    'trip-edit' => [
        'error' => 'Něco se pokazilo během upravování výletu',
        'success' => 'Výlet byl úspěšně upraven'
    ],
    'trip-invite' => [
        'error' => 'Něco se pokazilo během pozývání uživatele na tento výlet',
        'success' => 'Uživatel byl úspěšně pozván na tento výlet',
        'exists' => 'Tento uživatel už byl na tento výlet nedávno pozván. Počkejte prosím 10 minut',
        'missing' => 'Tato kombinace výletu a unikatního kódu se v naší databázi nenachází'
    ],
    'trip-invite-accept' => [
        'error' => 'Něco se pokazilo během přijímání pozvánky na výlet',
        'success' => 'Úspěšně jste přijali pozvánku na výlet',
        'access' => 'K tomuto výletu již patříte'
    ],
    'trip-delete' => [
        'error' => 'Něco se pokazilo během odstraňování výletu',
        'success' => 'Úspěšně jste odstranili výlet'
    ],
    'trip-comment' => [
        'traveller' => 'K tomuto výletu nepatříte a proto jej nemůžete okomentovat',
        'error' => 'Něco se pokazilo během komentování výletu',
        'success' => 'Úspěšně jste okomentovali výlet',
        'missing' => 'Vybraný komentář k výletu neexistuje'
    ],
    'trip-file' => [
        'missing' => 'Vybraný soubor výletu neexistuje'
    ],
    'trip-file-delete' => [
        'success' => 'Úspěšně jste odstranili soubor',
        'error' => 'Něco se pokazilo během odstraňování souboru'
    ],
    'trip-file-save' => [
        'success' => 'Úspěšně jste nahráli nový soubor',
        'error' => 'Něco se pokazilo během nahrávání nového souboru'
    ],
    'forgotten-password' => [
        'success' => 'Email s instrukcemi na obnovu hesla byl odeslán na vaši emailovou adresu',
        'error' => 'Něco se pokazilo během odesílání emailu na obnovu hesla'
    ],
    'reset-password' => [
        'success' => 'Vaše heslo bylo úspěšně obnoveno',
        'error' => 'Něco se pokazilo během obnovování hesla'
    ],
    'user' => [
        'missing' => 'Uživatel neexistuje'
    ],
    'admin' => [
        'delete-user' => [
            'success' => 'Úspěšně jste odstranili uživatele',
            'error' => 'Něco se pokazilo během odstraňování uživatele',
            'self' => 'Nemůžete odstranit sami sebe',
            'admin' => 'Nemůžete odstranit administrátora'
        ]
    ],
    'promote-user' => [
        'success' => 'Výletník byl úspěšně povýšen na organizátora',
        'error' => 'Něco se pokazilo během povyšování výletníka'
    ],
    'demote-user' => [
        'success' => 'Organizátor byl úspěšně propuštěn',
        'error' => 'Něco se pokazilo během propouštění organizátora'
    ]
];
