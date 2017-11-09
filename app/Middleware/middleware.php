<?php

return [
    'auth' => \App\Middleware\AuthMiddleware::class,
    'test1' => \App\Middleware\TestBeforeMiddleware::class,
    'test2' => \App\Middleware\TestAfterMiddleware::class,
    'always' => \App\Middleware\AlwaysLoadedMiddleware::class
];