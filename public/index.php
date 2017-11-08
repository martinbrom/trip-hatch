<?php

// TODO: displaying errors is turned on/off here
ini_set('display_errors', 1);

require dirname(__DIR__) . '/vendor/autoload.php';
$di = di();
$di->readFile("services.php");
require_once '../app/routes.php';

// TODO: Dashboard should be in trip not home
$request = $di->getService(\Core\Factories\RequestFactory::class)->make();
$di->getService(Core\Kernel::class)->handle($request);