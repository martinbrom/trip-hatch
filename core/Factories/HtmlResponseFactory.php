<?php

namespace Core\Factories;

use Core\Http\Response\HtmlResponse;
use Core\View;

/**
 * Class HtmlResponseFactory
 * @package Core\Factories
 * @author Martin Brom
 */
class HtmlResponseFactory
{
    /** @var View */
    private $view;

    /**
     * HtmlResponseFactory constructor.
     * @param View $view
     */
    public function __construct(View $view) {
        $this->view = $view;
    }

    /**
     * @param $template
     * @param array $data
     * @param int $code
     * @return HtmlResponse
     */
    public function make($template, $data = [], $code = 200) {
        return new HtmlResponse($this->view, $template, $data, $code);
    }
}