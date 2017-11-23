<?php

return [
    Core\Config\Config::class,
    Core\View::class,
    Core\Routing\Router::class,
    Core\Routing\RouteBuilder::class,
    Core\Kernel::class,
    Core\Language\Language::class,
    Core\Factories\RequestFactory::class,
    Core\Factories\ResponseFactory::class,
    App\Middleware\AddAlertsMiddleware::class,
    App\Middleware\AddViewDataMiddleware::class,
    App\Middleware\CsrfMiddleware::class,
    App\Middleware\UserAdminMiddleware::class,
    App\Middleware\UserLoggedMiddleware::class,
    App\Middleware\UserOrganiserMiddleware::class,
    App\Middleware\UserOwnerMiddleware::class,
    App\Middleware\UserTravellerMiddleware::class
];