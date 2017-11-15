<?php

namespace Core;

class Language
{
    const FOLDER = "../resources/lang";

    /** @var FlatArray */
    private $translations;
    private $locale;
    private $fallback;

    public function __construct(Config $config) {
        $this->translations = new FlatArray($this->loadTranslations());
        $this->locale = $config->get('base_locale');
        $this->fallback = $config->get('fallback_locale');
    }

    public function get($key, array $parameters = []) {
        $translation = $this->translations->get($this->locale . '.' . $key);
        if ($translation == null)
            $translation = $this->translations->get($this->fallback . '.' . $key);

        return $translation == null ? 'Translation [' . $key . '] missing' : $this->replace($translation, $parameters);
    }

    private function replace(string $string, array $replace = []) {
        if (!$replace) return $string;
        // TODO: Variable replacing
        return $string;
    }

    public function getAll() {
        return $this->translations->getAll();
    }

    private function loadTranslations() {
        $result = [];
        $dirs = glob(self::FOLDER . '/*', GLOB_ONLYDIR);
        foreach ($dirs as $dir) {
            $locale = basename($dir);
            $result[$locale] = $this->loadDirectory($dir);
        }

        return $result;
    }

    private function loadDirectory($directory) {
        $result = [];
        $files = glob($directory . '/*');

        foreach ($files as $file) {
            $key = basename($file, '.php');
            $result[$key] = require($file);
        }

        return $result;
    }
}