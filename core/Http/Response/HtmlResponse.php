<?php

namespace Core\Http\Response;

use Core\View;

/**
 * Class HtmlResponse
 * @package Core\Http\Response
 * @author Martin Brom
 */
class HtmlResponse extends Response
{
    /** @var View */
    private $view;

    /**
     * HtmlResponse constructor.
     * @param View $view
     * @param $template
     * @param array $data
     * @param int $code
     */
    public function __construct(View $view, $template, $data = [], $code = 200) {
        $this->view = $view;
        $this->addHeader('Content-type', 'text/html');
        $this->setContent($template);
        $this->setData($data);
        $this->setCode($code);
    }

    /**
     *
     */
    public function createContent() {
        return $this->view->render($this->getContent(), $this->getData());
    }

    /**
     *
     */
    protected function sendContent() {
        echo $this->createContent();
    }
}