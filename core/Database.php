<?php

class Database
{
    private $host = 'db';
    private $username = 'sonaro';
    private $password = 'insecuredevpassword';
    private $database = 'sonaro';
    private $connection;

    public function __construct()
    {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
