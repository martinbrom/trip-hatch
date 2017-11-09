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
    public function __construct(Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }

    /**
     * Returns all users from database
     * @return array All users from database
     */
    public function getUsers(): array {
        $query = "SELECT * FROM users";
        return $this->baseRepository->fetchAll($query);
    }

    /**
     * Returns first user with given email and password
     * @param string $email User email
     * @param string $password User password
     * @return array User with given email and password
     */
    public function getUser($email, $password): array {
        $query = "SELECT * FROM users
                INNER JOIN images ON users.image_id = images.id
                WHERE password = :password AND email = :email";
        $data = ['password' => $password, 'email' => $email];
        return $this->baseRepository->fetch($query, $data);
    }
}