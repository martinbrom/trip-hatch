<?php

namespace Core;

class AlwaysLoadedMiddleware implements Middleware
{
    public function before(): bool {
        echo "before middleware always loaded";
        return true;
    }

    public function after() {
        echo "after middleware always loaded";
    }
}