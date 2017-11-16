<?php

namespace Core\Language;

use Core\Config;
use Core\FlatArray;
use Core\Language\Exception\TranslationNotExistsException;

/**
 * Handles translation of application messages
 * @package Core
 * @author Martin Brom
 */
class Language
{
    /** Location of language files */
    const FOLDER = "../resources/lang";

    /** @var FlatArray Dictionary of translations */
    private $translations;

    /** @var mixed|null Base locale of the application */
    private $locale;

    /** @var mixed|null Fallback locale if base locale translation isn't found */
    private $fallback;

    /**
     * Creates new instance, injects config instance
     * and loads base and fallback locales from config
     * @param Config $config Instance containing configuration data
     */
    public function __construct(Config $config) {
        $this->translations = new FlatArray($this->loadTranslations());
        $this->locale = $config->get('base_locale');
        $this->fallback = $config->get('fallback_locale');
    }

    /**
     * Returns translation for a given key, replacing translation
     * parameter placeholders with actual parameters
     * @param string $key
     * @param array $parameters
     * @return string Prepared translation if key exists
     * @throws TranslationNotExistsException When translation doesn't exist in both locales
     */
    public function get(string $key, array $parameters = []) {
        $translation = $this->translations->get($this->locale . '.' . $key);
        if ($translation == null)
            $translation = $this->translations->get($this->fallback . '.' . $key);

        if ($translation == null)
            throw new TranslationNotExistsException($key, $this->locale, $this->fallback);

        return $this->replace($translation, $parameters);
    }

    /**
     * Replaces parameter placeholders with actual parameters
     * @param string $string Translation message with parameter placeholders
     * @param array $replace Array of parameters
     * @return string Prepared translation message
     */
    private function replace(string $string, array $replace = []): string {
        for ($i = 0; $i < count($replace); $i++) {
            $string = str_replace(':p' . ($i+1), $replace[$i], $string);
        }

        return $string;
    }

    /**
     * Returns all translations
     * @return array All translations
     */
    public function getAll(): array {
        return $this->translations->getAll();
    }

    /**
     * Loads translations from language directories
     * @return array Dictionary of translations
     */
    private function loadTranslations(): array {
        $result = [];
        $dirs = glob(self::FOLDER . '/*', GLOB_ONLYDIR);
        foreach ($dirs as $dir) {
            $locale = basename($dir);
            $result[$locale] = $this->loadDirectory($dir);
        }

        return $result;
    }

    /**
     * Loads translations from language files
     * @param string $directory Location of language files
     * @return array Dictionary of translations
     */
    private function loadDirectory(string $directory): array {
        $result = [];
        $files = glob($directory . '/*');

        foreach ($files as $file) {
            $key = basename($file, '.php');
            $result[$key] = require($file);
        }

        return $result;
    }
}