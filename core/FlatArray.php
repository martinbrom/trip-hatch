<?php

namespace Core;

class FlatArray
{
    private $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function get($flatKey) {
        $keys = explode('.', $flatKey);
        $array = $this->data;
        foreach ($keys as $key) {
            if (!is_array($array)) return null;
            $array = $array[$key];
        }

        return $array;
    }
    
    public function add($flatKey, $value) {
        $keys = explode('.', $flatKey);
        $array = &$this->data;
        foreach ($keys as $key) {
            if (!is_array($array)) return;
            $array = &$array[$key];
        }

        foreach ($value as $key => $val) {
            $array[$key] = $val;
        }
    }

    public function set($flatKey, $value) {
        $keys = explode('.', $flatKey);
        $array = &$this->data;
        foreach ($keys as $key) {
            if (!is_array($array)) return;
            $array = &$array[$key];
        }

        $array = $value;
    }

    public function getAll() {
        return $this->data;
    }
}