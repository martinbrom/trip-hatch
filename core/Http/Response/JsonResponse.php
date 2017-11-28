<?php

namespace Core\Http\Response;

/**
 * Class JsonResponse
 * @package Core\Http\Response
 * @author Martin Brom
 */
class JsonResponse extends Response
{
    /**
     * JsonResponse constructor.
     * @param null $content
     * @param int $code
     */
    public function __construct($content = null, $code = 200) {
        $this->addHeader('Content-type', 'application/json');
        $this->setContent($content);
        $this->setCode($code);
    }

    /**
     *
     */
    protected function sendContent() {
        echo \json_encode($this->getContent());
    }
}