<?php

namespace Core;

class TestBeforeMiddleware implements Middleware
{
    public function before(): bool {
        echo "before middleware";
        return true;
    }

    public function after() {}
}