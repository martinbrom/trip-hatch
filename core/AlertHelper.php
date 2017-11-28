<?php

namespace Core;

/**
 * Class AlertHelper
 * @package Core
 * @author Martin Brom
 */
class AlertHelper
{
    /** @var Session */
    private $session;

    /**
     * AlertFactory constructor.
     * @param Session $session
     */
    public function __construct(Session $session) {
        $this->session = $session;
    }

    /**
     * @param $type
     * @param $message
     * @param null $title
     */
    private function add($type, $message, $title = null) {
        $this->session->addTo('alerts', [
            'type' => $type,
            'message' => $message . '!',
            'title' => $title
        ]);
    }

    /**
     * @param $message
     * @param null $title
     */
    public function error($message, $title = null) {
        $this->add('danger', $message, $title);
    }

    /**
     * @param $message
     * @param null $title
     */
    public function warning($message, $title = null) {
        $this->add('warning', $message, $title);
    }

    /**
     * @param $message
     * @param null $title
     */
    public function info($message, $title = null) {
        $this->add('info', $message, $title);
    }

    /**
     * @param $message
     * @param null $title
     */
    public function success($message, $title = null) {
        $this->add('success', $message, $title);
    }
}