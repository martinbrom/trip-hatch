<?php

namespace App\Repositories;

use Core\Database\Repository;

/**
 * Instance of class provides simple functions,
 * that fetch data from Database, to be used in UserController
 * @package App\Repositories
 * @author Martin Brom
 */
class UserRepository
{
    /** @var Repository Instance of base repository used for communicating with database */
    private $baseRepository;

    /**
     * Creates UserRepository instance and injects Repository instance
     * @param Repository $baseRepository
     */
    public function __construct(\Core\Database\Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }

    /**
     * Returns all users from database
     * @return array All users from database
     */
    public function getUsers() {
        $query = "SELECT * FROM users";
        return $this->baseRepository->fetchAll($query);
    }
}