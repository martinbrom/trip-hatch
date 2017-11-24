<?php

namespace Core;

use Core\Config\Config;
use Core\Language\Language;
use Core\Routing\RouteHelper;

class View
{
    /** Location of view files */
    const FOLDER = '../resources/views';

    /** @var Language */
    private $language;

    /** @var RouteHelper */
    private $routeHelper;

    /** @var \Twig_Environment */
    private $twig;

    /** @var \Twig_Loader_Filesystem */
    private $loader;

    /** @var Config */
    private $config;

    /** @var bool */
    private $shouldCache;

    public function __construct(Language $language, RouteHelper $routeHelper, Config $config) {
        $this->language = $language;
        $this->routeHelper = $routeHelper;
        $this->config = $config;
        $this->shouldCache = $config->get('app.cache_views');
    }

    public function render($template, $args = []) {
        $this->loader = new \Twig_Loader_Filesystem(self::FOLDER);
        $this->initializeEnvironment();
        $this->registerFunctions();
        echo $this->twig->render($template, $args);
    }

    public function initializeEnvironment() {
        $args = $this->shouldCache ? ['cache' => '../temp/cache'] : [];
        $this->twig = new \Twig_Environment($this->loader, $args);
    }

    public function registerFunctions() {
        $this->twig->addFunction(new \Twig_Function('t', function ($key, $parameters = []) {
            return $this->language->get($key, $parameters);
        }));

        $this->twig->addFunction(new \Twig_Function('route', function ($name, $params = []) {
            return $this->routeHelper->get($name, $params);
        }));

        $this->twig->addFunction(new \Twig_Function('routeTrip', function ($route, $trip_id) {
            return $this->routeHelper->get($route, ['id' => $trip_id]);
        }));
    }
}