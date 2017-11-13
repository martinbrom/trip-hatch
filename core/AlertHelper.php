<?php

namespace Core;

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

    public function add($type, $message, $title = null) {
        $this->session->addTo('alerts', [
            'type' => $type,
            'message' => $message,
            'title' => $title
        ]);
    }

    public function error($message, $title = null) {
        $this->add('danger', $message, $title);
    }

    public function warning($message, $title = null) {
        $this->add('warning', $message, $title);
    }

    public function info($message, $title = null) {
        $this->add('info', $message, $title);
    }

    public function success($message, $title = null) {
        $this->add('success', $message, $title);
    }
}