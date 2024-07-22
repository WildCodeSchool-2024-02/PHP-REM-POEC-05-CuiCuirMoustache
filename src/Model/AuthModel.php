<?php

namespace App\Model;

use PDO;

class AuthModel extends AbstractManager
{
    public const TABLE = 'User';
    public const RESET_TOKENS_TABLE = 'PasswordResetTokens';

    public function register(
        string $username,
        string $firstName,
        string $lastName,
        string $email,
        string $password,
        //string $role,
        string $phone
    ): bool {
        $query = "INSERT INTO " . self::TABLE . " (username, first_name, last_name, email, password, phone) 
                  VALUES (:username, :first_name, :last_name, :email, :password, :phone)";
        $stmt = $this->pdo->prepare($query);

        return $stmt->execute([
            'username' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'phone' => $phone
        ]);
    }

    public function authenticate(string $email, string $password): bool
    {
        $query = "SELECT password FROM " . self::TABLE . " WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user && password_verify($password, $user['password']);
    }

    public function emailExists(string $email): bool
    {
        $query = "SELECT 1 FROM " . self::TABLE . " WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['email' => $email]);

        return $stmt->fetchColumn() !== false;
    }

    public function storeResetToken(string $email, string $token): bool
    {
        $query = "INSERT INTO " . self::RESET_TOKENS_TABLE . " (email, token, created_at) 
                  VALUES (:email, :token, NOW())";
        $stmt = $this->pdo->prepare($query);

        return $stmt->execute([
            'email' => $email,
            'token' => $token
        ]);
    }

    public function resetPassword(string $token, string $newPassword): bool
    {
        // Vérifiez que le token existe et n'a pas expiré
        $query = "SELECT email FROM " . self::RESET_TOKENS_TABLE . " WHERE token = :token AND TIMESTAMPDIFF(HOUR, created_at, NOW()) < 24";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['token' => $token]);
        
        $email = $stmt->fetchColumn();

        if ($email) {
            $query = "UPDATE " . self::TABLE . " SET password = :password WHERE email = :email";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                'password' => password_hash($newPassword, PASSWORD_BCRYPT),
                'email' => $email
            ]);

            // Supprimer le token utilisé
            $query = "DELETE FROM " . self::RESET_TOKENS_TABLE . " WHERE token = :token";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['token' => $token]);

            return $stmt->rowCount() > 0;
        }

        return false;
    }
}
