<?php

// TODO: displaying errors is turned on/off here
ini_set('display_errors', 1);
session_start();

require dirname(__DIR__) . '/vendor/autoload.php';
$di = di();
$di->readFile("services.php");
require_once '../app/routes.php';

$request = $di->getService(\Core\Factories\RequestFactory::class)->make();
$di->getService(Core\Kernel::class)->handle($request);