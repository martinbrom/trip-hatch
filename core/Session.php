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
        // TODO: Helper function maybe

        // return $this->exists($name) ? $_SESSION[$name] : null;
        $parts = explode('.', $name);

        $result = $_SESSION;
        foreach ($parts as $part) {
            if (!isset($result[$part])) return null;

            $result = $result[$part];
        }

        return $result;
    }

    public function delete($name) {
        unset($_SESSION[$name]);
    }
}
