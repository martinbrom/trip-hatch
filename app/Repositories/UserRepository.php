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
        return $this->baseRepository->select($this->table, 1)[0];
    }
    
    public function all() {
        return $this->baseRepository->select($this->table);
    }

    public function update($data) {
        $this->baseRepository->update($this->table, $data, ['id = ' . $data['id']]);
    }

    public function delete($data) {
        $this->baseRepository->delete($this->table, ['id = ' . $data['id']]);
    }
}