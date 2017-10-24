<?php

namespace Core;

use App\Models\TestModel;

class Database
{
    private $pdo;

    public function __construct(\Core\Config $config) {
        $dsn = "mysql:dbname=" . $config->get("db_name") . ";host=" . $config->get("db_host");
        $user = $config->get("db_username");
        $password = $config->get("db_password");

        try {
            $this->pdo = new \PDO($dsn, $user, $password);
        } catch (\PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function getAll($table) {
        $sql  = "SELECT * FROM $table";
        $stmt = $this->pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}