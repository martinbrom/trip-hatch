<?php

namespace Core;

/**
 * Interface defining Middleware class
 * Before function is run before request being processed,
 * and if all Middleware before functions complete without
 * error, request is processed and Middleware after
 * functions are ran after that.
 * @package Core
 * @author Martin Brom
 */
interface Middleware
{
    /**
     * Middleware method that is run after response is created
     * Returns whether the middleware ran without error
     * @return bool True if middleware ran without error, false otherwise
     */
    public function before(): bool;

    /**
     * Middleware method that is run after response is created
     * @return void
     */
    public function after();
}