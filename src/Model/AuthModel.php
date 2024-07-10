<?php

namespace App\Model;

use PDO;

class AuthModel extends AbstractManager
{
    public const TABLE = 'User';

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

    public function register(string $username, string $password, string $email, string $role, string $firstName, string $lastName, string $phone): bool
    {
        $query = "INSERT INTO " . self::TABLE . " (username, password, email, role, first_name, last_name, phone) VALUES (:username, :password, :email, :role, :first_name, :last_name, :phone)";
        $stmt = $this->pdo->prepare($query);

        return $stmt->execute([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'email' => $email,
            'role' => $role,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone
        ]);
    }

    public function authenticate(string $username, string $password): bool
    {
        $query = "SELECT password FROM " . self::TABLE . " WHERE username = :username";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'username' => $username,
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user && password_verify($password, $user['password']);
    }
}
