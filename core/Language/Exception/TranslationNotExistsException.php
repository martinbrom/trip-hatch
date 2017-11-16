<?php

namespace Core\Language\Exception;

use Exception;

class TranslationNotExistsException extends Exception
{
    public function __construct($key, $locale, $fallback) {
        $message = "Missing " . $key . " in both locales: [" . $locale . '|' . $fallback . ']';
        parent::__construct($message);
    }
}
