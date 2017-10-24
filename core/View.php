<?php

namespace Core;

class View
{
    // TODO: Check if rendered before rendering
    public static function render($template, $args = []) {
        static $twig = null;
        if ($twig === null) {
            $loader = new \Twig_Loader_Filesystem('../resources/views');
            /*
            $twig = new \Twig_Environment($loader, array(
                'cache' => '../temp/cache',
            ));
            */
            $twig = new \Twig_Environment($loader);
        }

        echo $twig->render($template, $args);
    }
}