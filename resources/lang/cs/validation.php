<?php

return [
    'login_email' => [
        'email' => 'Přihlašovací email musí být validní emailová adresa',
        'maxLen' => 'Přihlašovací email musí být nejvýše :p1 znaků dlouhý'
    ],
    'login_password' => [
        'required' => 'Pro přihlášení je nutné zadat přihlašovací heslo'
    ],
    'register_email' => [
        'required' => 'Pro registraci je nutné zadat registrační email',
        'email' => 'Registrační email musí být validní emailová adresa',
        'maxLen' => 'Registrační email musí být nejvýše :p1 znaků dlouhý',
        'unique' => 'Účet s tímto emailem už je zaregistrován'
    ],
    'register_password' => [
        'required' => 'Pro registraci je nutné zadat registrační heslo'
    ],
    'register_password_confirm' => [
        'required' => 'Pro registraci je nutné zopakovat heslo',
        'matches' => 'Obě zadaná hesla musí být stejná'
    ],
    'trip_title' => [
        'required' => 'Pro vytvoření výletu je nutné zadat název',
        'maxLen' => 'Název výletu musí být nejvýše :p1 znaků dlouhý'
    ],
    'day_title' => [
        'required' => 'Pro vytvoření dne je nutné zadat název',
        'maxLen' => 'Název dne musí být nejvýše :p1 znaků dlouhý'
    ],
    'action_content' => [
        'required' => 'Je nutné zadat obsah akce',
        'maxLen' => 'Obsah akce musí byt nejvýše :p1 znaků dlouhý'
    ],
    'action_title' => [
        'required' => 'Je nutné zadat název akce',
        'maxLen' => 'Název akce musí být nejvýše :p1 znaků dlouhý'
    ],
    'action_type' => [
        'required' => 'Je nutné zadat typ akce',
        'int' => 'Typ akce musí být kladné číslo',
        'exists' => 'Typ akce musí být platný typ akce'
    ],
    'action_edit_content' => [
        'required' => 'Je nutné zadah obsah akce',
        'maxLen' => 'Obsah akce musí byt nejvýše :p1 znaků dlouhý'
    ],
    'action_edit_title' => [
        'required' => 'Je nutné zadat název akce',
        'maxLen' => 'Název akce musí být nejvýše :p1 znaků dlouhý'
    ],
    'action_edit_type' => [
        'required' => 'Je nutné zadat typ akce',
        'int' => 'Typ akce musí být kladné číslo',
        'exists' => 'Typ akce musí být platný typ akce'
    ],
    'display_name' => [
        'maxLen' => 'Veřejné jméno musí být nejvýše :p1 znaků dlouhé'
    ],
    'old_password' => [
        'required' => 'Je nutné zadat původní heslo',
        'passwordVerify' => 'Původní heslo neodpovídá vašemu momentálnímu heslu'
    ],
    'new_password' => [
        'required' => 'Je nutné zadat nové heslo'
    ],
    'new_password_confirm' => [
        'required' => 'Je nutné zadat potvrzení nového hesla',
        'matches' => 'Potvrzení nového hesla musí odpovídat novému heslu'
    ],
    'invite_email' => [
        'required' => 'Je nutné zadat, na který email má být odeslána pozvánka',
        'email' => 'Email pozvánky musí být platná emailová adresa',
        'maxLen' => 'Email pozvánky musí být nejvýše :p1 znaků dlouhý'
    ],
    'invite_message' => [
        'maxLen' => 'Obsah pozvánky musí být nejvýše :p1 znaků dlouhý'
    ],
    'comment_content' => [
        'required' => 'Je nutné zadat obsah komentáře',
        'maxLen' => 'Obsah komentáře musí být nejvýše :p1 znaků dlouhý'
    ],
    'trip_file_title' => [
        'required' => 'Je nutné zadat název souboru',
        'maxLen' => 'Název souboru musí být nejvýše :p1 znaků dlouhý'
    ],
    'trip_file' => [
        'fileRequired' => 'Je nutné zvolit soubor',
        'fileMaxSize' => 'Zvolený soubor musí být nejvýše :p1 bytů velký',
        'fileType' => 'Zvolený soubor musí být jedním z následujících typů [:a]'
    ],
    'forgotten_password_email' => [
        'required' => 'Je nutné zadat email pro obnovu hesla',
        'email' => 'Email pro obnovu hesla musí být platná emailová adresa',
        'exists' => 'Tento email se v naší databázi nenachází'
    ],
    'reset_password' => [
        'required' => 'Je nutné zadat nové heslo'
    ],
    'reset_password_confirm' => [
        'required' => 'Je nutné zadat potvrzení nového hesla',
        'matches' => 'Potvrzení nového hesla musí odpovídat novému heslu'
    ]
];
