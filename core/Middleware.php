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
    public function before(): bool;
    public function after();
}