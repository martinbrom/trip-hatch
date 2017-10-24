<?php

$router->add('', 'Home', 'index');
$router->add('layout', 'Home', 'layout');
$router->add('dashboard', 'Home', 'dashboard');
$router->add('test', 'Test', 'index');
$router->add('trip/{id:\d+}', 'Trip', 'show');
$router->add('trip/add', 'Trip', 'add');