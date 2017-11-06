<?php

namespace Core;

use Closure;

class TestBeforeMiddleware implements Middleware
{
    public function handle(Request $request, Closure $next) {
        echo "processing before middleware";

        return $next($request);
    }
}