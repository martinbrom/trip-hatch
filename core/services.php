<?php

return [
    Core\Config::class,
    Core\View::class,
    Core\Routing\Router::class,
    Core\Kernel::class,
    Core\Language::class,
    Core\Factories\RequestFactory::class,
    Core\Factories\ResponseFactory::class,
    App\Middleware\AlwaysLoadedMiddleware::class,
    App\Middleware\AuthMiddleware::class,
    App\Middleware\TestAfterMiddleware::class,
    App\Middleware\TestBeforeMiddleware::class,
    App\Middleware\AddAlertsMiddleware::class
];