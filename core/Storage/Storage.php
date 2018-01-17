<?php

namespace Core\Storage;

use Core\Config\Config;

/**
 * Class Storage
 * @package Core\Storage
 * @author Martin Brom
 */
class Storage
{
    /** @var string */
    private $avatar_storage_path;

    /** @var string */
    private $trip_cover_storage_path;

    /** @var string */
    private $day_cover_storage_path;

    private $imageID;

    /**
     * Storage constructor.
     * @param Config $config
     */
    function __construct(Config $config) {
        $this->avatar_storage_path     = $config->get('storage.avatars');
        $this->trip_cover_storage_path = $config->get('storage.trip_cover');
        $this->day_cover_storage_path  = $config->get('storage.day_cover');
    }
    
    private function store($file, $file_name, $path) {
        if ($file_name == NULL)
            $file_name = token(32);

        // TODO: Move and create database entry

        // TODO: Image ID = last insert ID
    }
    
    public function storeAvatar($file, $file_name = NULL) {
        $this->store($file, $file_name, $this->avatar_storage_path);
    }
    
    public function storeTripCover($file, $file_name = NULL) {
        $this->store($file, $file_name, $this->trip_cover_storage_path);
    }

    public function storeDayCover($file, $file_name = NULL) {
        $this->store($file, $file_name, $this->day_cover_storage_path);
    }

    public function getImageID() {
        return $this->imageID;
    }
}
