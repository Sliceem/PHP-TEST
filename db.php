<?php

include 'config.php';

class Database
{
    private static $instance = null;
    private $pdo;
    private $query;
    private $error = false;
    private $results;
    private $count;

    private function __construct()
    {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    //All quarye's go through here
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Database;
        }
        return self::$instance;
    }
    //main query
    public function query($sql, $params = [])
    {
        $this->error = false;
        $this->query = $this->pdo->prepare($sql);

        if (count($params)) {
            $i = 1;
            foreach ($params as $param) {
                $this->query->bindValue($i, $param);
                $i++;
            }
        }
        //If execution went wrong, get an error
        if (!$this->query->execute()) {
            $this->error = true;
        } else {
            $this->results = $this->query->fetchAll();
            $this->count = $this->query->rowCount();
        }
        return $this;
    }
    //Get Specific data from Database 
    public function get($table, $where = [])
    {
        return $this->action('SELECT *', $table, $where);
    }
    //Delete specific data from Database
    public function delete($table, $where = [])
    {
        return $this->action('DELETE', $table, $where);
    }
    //Action for Get and Delete query
    public function action($action, $table, $where = [])
    {
        if (count($where) === 3) {
            $operators = ['=', '>', '<', '<=', '>=', '!='];
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];
            //Check if operator is confirmed
            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                //If there are no errors
                if (!$this->query($sql, [$value])->error()) {
                    return $this;
                }
            }
        }
        return false;
    }

    //Insert method
    public function insert($table, $fields = [])
    {
        $values = '';
        foreach ($fields as $field) {
            $values .= "?,";
        }
        $values = rtrim($values, ',');
        $sql = "INSERT INTO {$table} (`" . implode('`, `', array_keys($fields)) . "`) VALUES ({$values})";
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }

        return false;
    }

    //Update Method

    public function update($table, $id, $fields = [])
    {
        $set = '';
        foreach ($fields as $key => $field) {
            $set .= "{$key} = ?,";
        }
        $set = rtrim($set, ',');
        $sql = "UPDATE {$table} SET {$set} WHERE user_id = {$id}";
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }

    //Error method -> true OR false
    public function error()
    {
        return $this->error;
    }
    //Return result
    public function results()
    {
        return $this->results;
    }
    //count's fetched rows
    public function count()
    {
        return $this->count;
    }

    public function first(){
        return $this->results()[0];
    }
}
