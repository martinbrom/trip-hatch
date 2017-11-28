<?php

namespace Core\Language\Exception;

use Exception;

/**
 * Class TranslationNotExistsException
 * @package Core\Language\Exception
 * @author Martin Brom
 */
class TranslationNotExistsException extends Exception
{
    /**
     * TranslationNotExistsException constructor.
     * @param string $key
     * @param string $locale
     * @param string $fallback
     */
    public function __construct($key, $locale, $fallback) {
        $message = "Missing " . $key . " in both locales: [" . $locale . '|' . $fallback . ']';
        parent::__construct($message);
    }
}
