<?php

namespace App\Middleware;

use Core\Middleware;

class TestBeforeMiddleware implements Middleware
{
    public function before(): bool {
        echo "before middleware";
        return true;
    }

    public function after() {}
}