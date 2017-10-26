<?php

namespace Core;

class Database
{
    // TODO: Maybe clear variables after finishing query???
    private $pdo;
    private $table;
    private $data;
    private $lastInsertId;

    public function __construct(\Core\Config $config) {
        $dsn = "mysql:dbname=" . $config->get("db_name") . ";host=" . $config->get("db_host");
        $user = $config->get("db_username");
        $password = $config->get("db_password");
        $settings = $config->get("db_settings");

        try {
            $this->pdo = new \PDO($dsn, $user, $password, $settings);
        } catch (\PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
    
    public function table($tableName) {
        $this->table = $tableName;
        return $this;
    }
    
    public function data($data) {
        $this->data = $data;
        return $this;
    }

    public function insert($sql) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->data);
        $this->lastInsertId = $this->pdo->lastInsertId();
    }

    public function update($sql) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->data);
    }

    public function delete($sql) {
        $this->pdo->prepare($sql)->execute();
    }

    public function select($sql) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}