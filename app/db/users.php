<?php

namespace Database;

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../models/getUserDto.php';
require_once __DIR__ . '/../models/createUserDto.php';

use Models\CreateUserDto;
use Models\GetUserDto;
use PDO;

class UsersDatabase extends Database
{
    private readonly string $tblName;

    public function __construct()
    {
        parent::__construct();
        $this->tblName = "tblUsers";
    }

    /**
     * Summary of createUser
     * user id and other fields are automatically intialised through mysql.
     * @param string $email
     * @param string $hashedPassword
     * @return bool successful or not
     */
    public function createUser(CreateUserDto $user): bool
    {
        $sql = "INSERT INTO {$this->tblName} (email, password) VALUES (:e, :p)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":e" => $user->email,
            ":p" => $user->password
        ]);
    }

    /**
     * Summary of getUserByEmail
     * @param int $email
     * @return GetUserDto|null return the User object or null if nothing
     */
    public function getUserByEmail(string $email): ?GetUserDto
    {
        $sql = "SELECT * FROM {$this->tblName} WHERE email = :e";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":e" => $email
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? new GetUserDto(
            (int) $result['id'],
            $result['email'],
            $result['is_admin'] == 1 ? true : false,
            $result['password']
        ) : null;
    }
}