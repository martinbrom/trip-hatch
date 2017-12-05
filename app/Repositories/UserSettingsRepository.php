<?php

namespace App\Repositories;

use Core\Database\Repository;

/**
 * Class UserSettingsRepository
 * @package App\Repositories
 * @author Martin Brom
 */
class UserSettingsRepository
{
    /** @var Repository */
    private $baseRepository;

    /**
     * UserSettingsRepository constructor.
     * @param Repository $baseRepository
     */
    function __construct(Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }

    /**
     * @param $user_id
     * @param $display_name
     * @return bool
     */
    public function changeDisplayName($user_id, $display_name) {
        $query = "UPDATE users
                SET display_name = :display_name
                WHERE id = :id";
        $data = ['id' => $user_id, 'display_name' => $display_name];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @param $user_id
     * @param $password
     * @return bool
     */
    public function changePassword($user_id, $password) {
        $query = "UPDATE users
                SET `password` = :password
                WHERE `id` = :id";
        $data = ['id' => $user_id, 'password' => $password];
        return $this->baseRepository->run($query, $data);
    }
}