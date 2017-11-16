<?php

return [
    Core\Config::class,
    Core\View::class,
    Core\Routing\Router::class,
    Core\Kernel::class,
    Core\Language\Language::class,
    Core\Factories\RequestFactory::class,
    Core\Factories\ResponseFactory::class,
    App\Middleware\AuthMiddleware::class,
    App\Middleware\AddAlertsMiddleware::class,
    App\Middleware\AddViewDataMiddleware::class,
    App\Middleware\CsrfMiddleware::class
];