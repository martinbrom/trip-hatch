<?php

namespace App\Repositories;

class UserRepository
{
    private $baseRepository;
    private $table = 'users';

    public function __construct(\Core\Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }

    public function insert($data) {
        $this->baseRepository->insert($this->table, $data);
    }

    public function first() {
        return $this->baseRepository->select($this->table, [], [],  [], 1)[0];
    }
    
    public function all() {
        return $this->baseRepository->select($this->table);
    }

    public function onlyWithDisplayName() {
        return $this->baseRepository->select($this->table, ['display_name IS NOT NULL']);
    }

    public function emails() {
        return $this->baseRepository->select($this->table, [], ['users.email']);
    }
    
    public function complexEmails() {
        return $this->baseRepository->select($this->table, ['users.display_name IS NOT NULL'], ['users.email'], [], 3, 2);
    }

    public function firstWithImage() {
        return $this->baseRepository->select($this->table, [], [], ['images' => ['type' => 'INNER', 'condition' => 'users.image_id = images.id']], 1)[0];
    }

    public function update($data) {
        $this->baseRepository->update($this->table, $data, ['id = ' . $data['id']]);
    }

    public function delete($data) {
        $this->baseRepository->delete($this->table, ['id = ' . $data['id']]);
    }
}