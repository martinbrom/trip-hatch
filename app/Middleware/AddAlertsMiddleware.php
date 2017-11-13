<?php

namespace App\Middleware;

use Core\Middleware;

class AddAlertsMiddleware implements Middleware
{
    public function before(): bool { return true; }

    public function after() {
        // TODO: Move alerts from session to view
    }
}