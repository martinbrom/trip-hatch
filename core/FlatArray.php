<?php

namespace Core;

class FlatArray
{
    private $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function get(string $key = null) {
        if (is_null($key)) return $this->data;

        $keys = explode('.', $key);
        $value = $this->data;

        foreach ($keys as $k) {
            if (!is_array($value) || !array_key_exists($k, $value)) return null;
            $value = $value[$k];
        }
        
        return $value;
    }

    public function set(string $key, $value) {
        $keys = explode('.', $key);
        $array = &$this->data;

        foreach ($keys as $k) {
            if (!is_array($array)) $array = [];
            if (!array_key_exists($k, $array)) $array[$key] = null;
            $array = &$array[$k];
        }
        
        $array[end($keys)] = $value;
    }

    public function has(string $key): bool {
        return $this->get($key) == null;
    }

    public function getAll() {
        return $this->data;
    }
}