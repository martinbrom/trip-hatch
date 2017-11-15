<?php

namespace Core;

/**
 * Class Language
 * @package Core
 * @author Martin Brom
 */
class Language
{
    /** Location of language files */
    const FOLDER = "../resources/lang";

    /** @var FlatArray */
    private $translations;

    /** @var mixed|null */
    private $locale;

    /** @var mixed|null */
    private $fallback;

    /**
     * Language constructor.
     * @param Config $config
     */
    public function __construct(Config $config) {
        $this->translations = new FlatArray($this->loadTranslations());
        $this->locale = $config->get('base_locale');
        $this->fallback = $config->get('fallback_locale');
    }

    /**
     * @param string $key
     * @param array $parameters
     * @return mixed|string
     */
    public function get(string $key, array $parameters = []) {
        $translation = $this->translations->get($this->locale . '.' . $key);
        if ($translation == null)
            $translation = $this->translations->get($this->fallback . '.' . $key);

        return $translation == null ? 'Translation [' . $key . '] missing' : $this->replace($translation, $parameters);
    }

    /**
     * @param string $string
     * @param array $replace
     * @return string
     */
    private function replace(string $string, array $replace = []): string {
        for ($i = 0; $i < count($replace); $i++) {
            $string = str_replace(':p' . ($i+1), $replace[$i], $string);
        }

        return $string;
    }

    /**
     * @return array
     */
    public function getAll(): array {
        return $this->translations->getAll();
    }

    /**
     * @return array
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
     * @param string $directory
     * @return array
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