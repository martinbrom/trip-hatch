<?php

namespace Core;

/**
 * Class FlatArray
 * @package Core
 * @author Martin Brom
 */
class FlatArray
{
    /** @var array */
    private $data;

    /**
     * FlatArray constructor.
     * @param array $data
     */
    public function __construct(array $data) {
        $this->data = $data;
    }

    /**
     * @param string|null $key
     * @return array|mixed|null
     */
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

    /**
     * @param string $key
     * @param $value
     */
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

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool {
        return $this->get($key) == null;
    }

    /**
     * @return array
     */
    public function getAll() {
        return $this->data;
    }
}