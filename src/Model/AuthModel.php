<?php

namespace App\Model;

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
        string $phone
    ): bool {
        $query = "INSERT INTO " . self::TABLE . " 
            (username, first_name, last_name, email, password, phone, role) 
            VALUES (:username, :first_name, :last_name, :email, :password, :phone, :role)";
        $stmt = $this->pdo->prepare($query);

        return $stmt->execute([
            'username' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'role' => 'user'
        ]);
    }

    public function authenticate(string $email, string $password)
    {
        $query = "SELECT * FROM " . self::TABLE . " WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            $encryptionKey = 'votre_clé_de_chiffrement';
            $decodedPassword = $this->decodePassword($user['password'], $encryptionKey);

            if ($decodedPassword === hash('sha256', $password, true)) {
                return $user;
            }
        }
        return false;
    }

    public function updateUser(int $userId, array $data): bool
    {
        $query = "UPDATE " . self::TABLE . " SET 
              username = :username, 
              email = :email, 
              first_name = :firstname, 
              last_name = :lastname, 
              phone = :phone 
              WHERE id = :id";
        $stmt = $this->pdo->prepare($query);

            return $stmt->execute([
                'username' => $data['username'],
                'email' => $data['email'],
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'phone' => $data['phone'],
                'id' => $userId
            ]);
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
        $query = "SELECT email FROM " . self::RESET_TOKENS_TABLE .
        " WHERE token = :token AND TIMESTAMPDIFF(HOUR, created_at, NOW()) < 24";
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

    private function decodePassword($encodedPassword, $encryptionKey)
    {
        $decoded = base64_decode($encodedPassword);
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $ivSubstr = substr($decoded, 0, $ivLength);
        $encryptedPassword = substr($decoded, $ivLength);
        $decrypted = openssl_decrypt($encryptedPassword, 'aes-256-cbc', $encryptionKey, 0, $ivSubstr);

        return $decrypted;
    }
}
