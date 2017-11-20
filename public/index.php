<?php

use Core\DependencyInjector\DependencyInjector;
use Core\Factories\RequestFactory;
use Core\Http\Response\Response;
use Core\Kernel;

// TODO: displaying errors is turned on/off here
ini_set('display_errors', 1);
session_start();

require dirname(__DIR__) . '/vendor/autoload.php';
$di = new DependencyInjector();
$di->readFile("../services.php");
require_once '../app/routes.php';

$request = $di->getService(RequestFactory::class)->make();

/** @var Response $response */
$response = $di->getService(Kernel::class)->handle($request);
$response->send();