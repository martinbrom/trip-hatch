<?php

return [
    'db_host' => '127.0.0.1',
    'db_username' => 'root',
    'db_password' => 'password',
    'db_name' => 'triphatch',
    'db_settings' => [
        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        \PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_EMULATE_PREPARES => false
    ]
];
