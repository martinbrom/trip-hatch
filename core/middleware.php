<?php

return [
    'alerts' => App\Middleware\AddAlertsMiddleware::class,
    'auth' => App\Middleware\AuthMiddleware::class,
    'csrf' => App\Middleware\CsrfMiddleware::class,
    'viewData' => App\Middleware\AddViewDataMiddleware::class
];
