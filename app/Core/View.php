<?php

namespace App\Core;

class View
{
    public static function render($template, $args = []) {
        static $twig = null;
        if ($twig === null) {
            $loader = new \Twig_Loader_Filesystem('../resources/views');
            $twig = new \Twig_Environment($loader);
        }

        echo $twig->render($template, $args);
    }
}