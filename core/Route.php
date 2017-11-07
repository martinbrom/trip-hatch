<?php

namespace Core;

class Route
{
    private $pattern;
    private $controller;
    private $action;
    private $method;
    private $middleware;
    private $ajaxOnly;

    public function __construct($pattern, $controller, $action, $method = "GET") {
        $this->pattern = $pattern;
        $this->controller = $controller;
        $this->action = $action;
        $this->method = $method;
        $this->middleware = [];
        $this->ajaxOnly = false;
    }

    public function middleware($middleware): self {
        $this->middleware = $middleware;
        return $this;
    }

    public function ajax(): self {
        $this->ajaxOnly = true;
        return $this;
    }

    public function getPattern() {
        return $this->pattern;
    }

    public function getController() {
        return $this->controller;
    }

    public function getAction() {
        return $this->action;
    }

    public function getMethod(): string {
        return $this->method;
    }

    public function getMiddleware(): array {
        return $this->middleware;
    }

    public function isAjaxOnly(): bool {
        return $this->ajaxOnly;
    }
}