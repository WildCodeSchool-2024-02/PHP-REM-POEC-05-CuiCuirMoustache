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

    public function register(
        string $firstName,
        string $lastName,
        string $email,
        string $password,
        string $role,
        string $phone
    ): bool {
        $query = "INSERT INTO " . self::TABLE . " (firstname, lastname, email, password, role, phone) 
                  VALUES (:firstname, :lastname, :email, :password, :role, :phone)";
        $stmt = $this->pdo->prepare($query);

        return $stmt->execute([
            'firstname' => $firstName,
            'lastname' => $lastName,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role' => $role,
            'phone' => $phone
        ]);
    }

    public function authenticate(string $username, string $password): bool
    {
        $query = "SELECT password FROM " . self::TABLE . " WHERE username = :username";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['username' => $username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user && password_verify($password, $user['password']);
    }
}
