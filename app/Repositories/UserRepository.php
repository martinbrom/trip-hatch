<?php

namespace App\Repositories;

class UserRepository
{
    private $baseRepository;

    public function __construct(\Core\Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }

    public function getUsers() {
        $query = "SELECT * FROM users";
        return $this->baseRepository->fetchAll($query);
    }
}