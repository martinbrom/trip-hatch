<?php

namespace Core;

class Session
{
    public function exists($name): bool {
        return isset($_SESSION[$name]);
    }

    public function put($name, $value) {
        $_SESSION[$name] = $value;
    }

    public function get($name) {
        return $this->exists($name) ? $_SESSION[$name] : null;
    }

    public function delete($name) {
        unset($_SESSION[$name]);
    }
}
