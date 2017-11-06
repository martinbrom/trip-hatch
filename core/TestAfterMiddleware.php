<?php

namespace Core;

use Closure;

class TestAfterMiddleware implements Middleware
{
    public function handle(Request $request, Closure $next) {
        echo "processing after middleware";

        $response = $next($request);

        return $response;
    }
}