<?php

namespace App\Model;

use PDO;

class AuthModel extends AbstractManager
{
    public const TABLE = 'users'; 

    /**
     * Method to verify user login information.
     *
     * @param string $username Username
     * @param string $password Password
     * @return bool Returns true if authentication is successful, otherwise false
     */
    public function authenticate(string $username, string $password): bool
    {
        $query = "SELECT id FROM " . self::TABLE . " WHERE username = :username AND password = :password";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'username' => $username,
            'password' => md5($password)
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? true : false;
    }
}
