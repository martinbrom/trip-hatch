<?php

namespace Core\Http\Response;

use Core\View;

class HtmlResponse extends Response
{
    /** @var View */
    private $view;

    public function __construct(View $view, $template, $data = [], $code = 200) {
        $this->view = $view;
        $this->addHeader('Content-type', 'text/html');
        $this->setContent($template);
        $this->setData($data);
        $this->setCode($code);
    }

    protected function sendContent() {
        $this->view->render($this->getContent(), $this->getData());
    }
}