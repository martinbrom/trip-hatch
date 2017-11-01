<?php

namespace Core;

class Database
{
    private $pdo;
    private $config;
    private $statement;

    public function __construct(\Core\Config $config) {
        $this->config = $config;
        $this->initConnection();
    }

    public function initConnection() {
        $dsn = 'mysql:dbname=' . $this->config->get('db_name') . ';host=' . $this->config->get('db_host');
        $username = $this->config->get('db_username');
        $password = $this->config->get('db_password');
        $settings = [
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => false
        ];

        $this->pdo = new \PDO($dsn, $username, $password, $settings);

    }

    public function query($query, $params = []) {
        $this->statement = $this->pdo->prepare($query);
        $this->statement->execute($params);
        return $this;
    }

    public function fetch() {
        return $this->statement->fetch();
    }

    public function fetchAll() {
        return $this->statement->fetchAll();
    }

    public function count() {
        return $this->statement->rowCount();
    }
}