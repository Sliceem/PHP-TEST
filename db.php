<?php

include 'config.php';

class Database
{
    public static $instance = null;
    private $pdo;

    private function __construct()
    {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS);
            echo 'ok';
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    protected static function getinstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Database;
        }
    }
}
