<?php

namespace Core\Factories;

use Core\Http\Response\HtmlResponse;
use Core\View;

class HtmlResponseFactory
{
    /** @var View */
    private $view;

    public function __construct(View $view) {
        $this->view = $view;
//        var_dump($view);
//        die();
    }

    public function make($template, $data = [], $code = 200) {
        return new HtmlResponse($this->view, $template, $data, $code);
    }
}