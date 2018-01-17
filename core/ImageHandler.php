<?php

namespace Core;

/**
 * Class ImageHandler
 * @package Core
 * @author Martin Brom
 */
class ImageHandler
{
    private $image;
    private $result;
    private $empty;
    private $validationError;
    private $allowedExtensions = ['jpg', 'jpeg', 'png'];

    public function resize(int $width, int $height) {}

    public function resizeAvatar() {
        $this->resize(256, 256);
    }

    public function resizeTripCover() {
        $this->resize(720, 405);
    }

    public function resizeDayCover() {
        $this->resize(100, 100);
    }

    public function processAvatar($image) {}

    public function processTripCover($image) {}

    public function processDayCover($image) {
        $error = $image['error'];
        $this->validationError = NULL;

        if ($error == 4) {
            $this->empty = true;
            return;
        }

        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        if (!in_array($extension, $this->allowedExtensions)) {
            $this->validationError = 'extension';
            return;
        }

        if ($error == 0)
            return;

        switch ($error) {
            case 1: case 2: $this->validationError = 'max-filesize';break;
            case 3: $this->validationError = 'partial';break;
            case 7: $this->validationError = 'write';break;
            default: $this->validationError = 'default';break;
        }
    }

    public function getResult() {
        return $this->result;
    }

    public function isEmpty() {
        return $this->empty;
    }

    public function isInvalid() {
        return $this->validationError != NULL;
    }

    public function getValidationError() {
        return $this->validationError;
    }
}
