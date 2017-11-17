<?php

return [
    'alerts' => App\Middleware\AddAlertsMiddleware::class,
    'logged' => App\Middleware\UserLoggedMiddleware::class,
    'traveller' => App\Middleware\UserTravellerMiddleware::class,
    'organiser' => App\Middleware\UserOrganiserMiddleware::class,
    'owner' => App\Middleware\UserOwnerMiddleware::class,
    'admin' => App\Middleware\UserAdminMiddleware::class,
    'csrf' => App\Middleware\CsrfMiddleware::class,
    'viewData' => App\Middleware\AddViewDataMiddleware::class
];
