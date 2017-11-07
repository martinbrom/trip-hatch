<?php

namespace Core;

class TestAfterMiddleware implements Middleware
{
    public function before(): bool { return true; }

    public function after() {
        echo "after middleware";
    }
}