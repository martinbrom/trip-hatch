<?php

return [
    Core\Config::class,
    Core\View::class,
    Core\Routing\Router::class,
    Core\Kernel::class,
    Core\Factories\RequestFactory::class,
    App\Middleware\AlwaysLoadedMiddleware::class,
    App\Middleware\AuthMiddleware::class,
    App\Middleware\TestAfterMiddleware::class,
    App\Middleware\TestBeforeMiddleware::class
];