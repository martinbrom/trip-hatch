<?php

namespace App\Middleware;

use Core\Middleware;

class TestBeforeMiddleware extends Middleware
{
    public function before(): bool {
        echo "before middleware";
        return true;
    }

    public function after() {}
}