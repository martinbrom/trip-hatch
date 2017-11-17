<?php

namespace Core;

use Core\Language\Language;

class View
{
    /** @var Language */
    private $language;

    public function __construct(Language $language) {
        $this->language = $language;
    }

    public function render($template, $args = []) {
        echo $this->getTemplate($template, $args);
    }

    public function getTemplate($template, $args = []) {
        $loader = new \Twig_Loader_Filesystem('../resources/views');
        /*
        $twig = new \Twig_Environment($loader, array(
            'cache' => '../temp/cache',
        ));
        */
        $twig = new \Twig_Environment($loader);
        $twig->addFunction(new \Twig_Function('t', function ($key, $parameters = []) {
            return $this->language->get($key, $parameters);
        }));

        return $twig->render($template, $args);
    }
}