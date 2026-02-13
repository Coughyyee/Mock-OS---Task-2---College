<?php

namespace Database;

use PDO;
use PDOException;

abstract class Database
{
    private readonly string $servername;
    private readonly string $dbName;
    private readonly string $username;
    private readonly string $password;
    final protected PDO $conn;

    public function __construct()
    {
        $this->servername = 'localhost';
        $this->dbName = 'health-group-db';
        $this->username = 'root';
        $this->password = '';

        // Automatically runs connect function on class initialisation.
        $this->connect();
    }

    // Cannot be overriden, only called. Subclasses will call the connect function.
    final protected function connect()
    {
        try {
            $this->conn = new PDO("mysql:host={$this->servername};dbname={$this->dbName}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            exit('Database connection error: ' . $e->getMessage());
        }
    }
}