<?php

namespace Core;

interface Middleware
{
    public function before(): bool;
    public function after();
}