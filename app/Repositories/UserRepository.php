<?php

namespace App\Repositories;

use Core\Database\Repository;

/**
 * Instance of class provides simple functions,
 * that fetch data from Database
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
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function getNewUsers($limit = 100, $page = 0): array {
        // TODO: Maybe validate limit and page
        $date = date('Y-m-d H:i:s', strtotime('-1 week'));
        $offset = $page * $limit;
        $query = "SELECT * FROM users
                WHERE created_at <= '$date'
                LIMIT $limit OFFSET $offset";
        return $this->baseRepository->fetchAll($query);
    }

    /**
     * @return int
     */
    public function getNewCount(): int {
        $date = date('Y-m-d H:i:s', strtotime('-1 week'));
        $query = "SELECT COUNT(*) as count FROM users
                WHERE created_at <= '$date'";
        return $this->baseRepository->fetch($query)['count'];
    }

    /**
     * Returns first user with given email
     * @param string $email User email
     * @return array User with given email
     */
    public function getUser($email): array {
        $query = "SELECT * FROM users
                INNER JOIN images ON users.image_id = images.id
                WHERE email = :email";
        $data = ['email' => $email];
        return $this->baseRepository->fetch($query, $data);
    }

    /**
     * @param $user_id
     * @param $password
     * @return array
     */
    public function getUserByPassword($user_id, $password): array {
        $query = "SELECT * FROM users
                WHERE id = :id AND password = :password";
        $data = ['id' => $user_id, 'password' => $password];
        return $this->baseRepository->fetch($query, $data);
    }

    public function createUser($email, $hash) {
        $query = "INSERT INTO `users` (
                `id`, `email`, `password`, `display_name`, `is_admin`,
                `image_id`, `created_at`, `updated_at`)
                VALUES (NULL, :email, :hash, NULL, '0',
                '1', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $data = ['email' => $email, 'hash' => $hash];
        return $this->baseRepository->run($query, $data);
    }
}