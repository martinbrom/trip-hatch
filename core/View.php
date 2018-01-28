<?php

namespace Core;

use Core\Config\Config;
use Core\Language\Language;
use Core\Routing\RouteHelper;

/**
 * Class View
 * @package Core
 * @author Martin Brom
 */
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

    /**
     * View constructor.
     * @param Language $language
     * @param RouteHelper $routeHelper
     * @param Config $config
     */
    public function __construct(Language $language, RouteHelper $routeHelper, Config $config) {
        $this->language = $language;
        $this->routeHelper = $routeHelper;
        $this->config = $config;
        $this->shouldCache = $config->get('app.cache_views');
    }

    /**
     * @param $template
     * @param array $args
     * @return string
     */
    public function render($template, $args = []) {
        $this->loader = new \Twig_Loader_Filesystem(self::FOLDER);
        $this->initializeEnvironment();
        $this->registerFunctions();
        return $this->twig->render($template, $args);
    }

    /**
     *
     */
    public function initializeEnvironment() {
        $args = $this->shouldCache ? ['cache' => '../temp/cache'] : [];
        $this->twig = new \Twig_Environment($this->loader, $args);
    }

    /**
     *
     */
    public function registerFunctions() {
        $this->twig->addFunction(new \Twig_Function('t', function ($key, $parameters = []) {
            return $this->language->get($key, $parameters);
        }));

        $this->twig->addFunction(new \Twig_Function('route', function ($name, $params = []) {
            return $this->routeHelper->get($name, $params);
        }));

        $this->twig->addFunction(new \Twig_Function('routeTrip', function ($route, $trip_id) {
            return $this->routeHelper->get('trip.' . $route, ['trip_id' => $trip_id]);
        }));

        $this->twig->addFunction(new \Twig_Function('routeTripUser', function ($route, $trip_id, $user_trip_id) {
            return $this->routeHelper->get('trip.user.' . $route, ['trip_id' => $trip_id, 'user_trip_id' => $user_trip_id]);
        }));

        $this->twig->addFunction(new \Twig_Function('routeTripPublic', function ($url) {
            return $this->routeHelper->get('trip.public', ['public_url' => $url]);
        }));

        $this->twig->addFunction(new \Twig_Function('routeTripDay', function ($route, $trip_id, $day_id) {
            return $this->routeHelper->get('trip.day.' . $route, ['trip_id' => $trip_id, 'day_id' => $day_id]);
        }));

        $this->twig->addFunction(new \Twig_Function('routeTripDayAction', function ($route, $trip_id, $day_id, $action_id) {
            return $this->routeHelper->get('trip.day.action.' . $route, ['trip_id' => $trip_id, 'day_id' => $day_id, 'action_id' => $action_id]);
        }));

        $this->twig->addFunction(new \Twig_Function('routeTripComment', function ($route, $trip_id, $comment_id) {
            return $this->routeHelper->get('trip.comments.' . $route, ['trip_id' => $trip_id, 'comment_id' => $comment_id]);
        }));

        $this->twig->addFunction(new \Twig_Function('routeTripFile', function ($route, $trip_id, $file_id) {
            return $this->routeHelper->get('trip.files.' . $route, ['trip_id' => $trip_id, 'file_id' => $file_id]);
        }));
    }
}