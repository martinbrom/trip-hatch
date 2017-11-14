<?php

namespace Core;

class Language
{
    const FOLDER = "/resources/lang";

    /** @var FlatArray */
    private $translations;
    private $locale;
    private $fallback;

    public function __construct(Config $config) {
        $this->translations = new FlatArray($this->loadTranslations());
        $this->locale = $config->get('base_locale');
        $this->fallback = $config->get('fallback_locale');
    }

    public function get($key) {
        $translation = $this->translations->get($this->locale . '.' . $key);
        if ($translation == null)
            $translation = $this->translations->get($this->fallback . '.' . $key);

        return $translation == null ? 'Translation [' . $key . ' missing!' : $translation;
    }

    private function loadTranslations() {
        return [
            'a' => 'aaa',
            'b' => 'bbb',
            'c' => [
                'd' => 'ddd',
                'e' => [
                    'f' => 'fff',
                    'g' => 'ggg'
                ]
            ]
        ];
    }
}