<?php

namespace Core;

/**
 * Class Session
 * @package Core
 * @author Martin Brom
 */
class Session
{
    /**
     * @param $name
     * @return bool
     */
    public function exists($name): bool {
        return isset($_SESSION[$name]);
    }

    /**
     * @param $name
     * @param $value
     */
    public function set($name, $value) {
        $_SESSION[$name] = $value;
    }

    /**
     * @param $array_name
     * @param $value
     */
    public function addTo($array_name, $value) {
        $_SESSION[$array_name] []= $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function get($name) {
        // return $this->exists($name) ? $_SESSION[$name] : null;
        $parts = explode('.', $name);

        $result = $_SESSION;
        foreach ($parts as $part) {
            if (!isset($result[$part])) return null;

            $result = $result[$part];
        }

        return $result;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function pop($name) {
        $item = $this->get($name);
        $this->delete($name);
        return $item;
    }

    /**
     * @param $name
     */
    public function delete($name) {
        unset($_SESSION[$name]);
    }

    /**
     * @return mixed
     */
    public function getAll() {
        return $_SESSION;
    }
}
