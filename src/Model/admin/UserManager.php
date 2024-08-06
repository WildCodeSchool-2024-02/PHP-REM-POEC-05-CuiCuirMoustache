<?php

namespace App\Model\admin;

use App\Model\AbstractManager;
use PDO;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    /**
     * Insert new user in database
     */
    public function insert(array $user): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (
            `username`,
            `password`,
            `email`,
            `role`,
            `first_name`,
            `last_name`,
            `phone`
            )
            VALUES (:username, :password, :email, :role, :first_name, :last_name, :phone)");

        $statement->bindValue('username', $user['username'], PDO::PARAM_STR);
        $statement->bindValue('password', password_hash($user['password'], PASSWORD_BCRYPT), PDO::PARAM_STR);
        $statement->bindValue('email', $user['email'], PDO::PARAM_STR);
        $statement->bindValue('role', $user['role'], PDO::PARAM_STR);
        $statement->bindValue('first_name', $user['first_name'], PDO::PARAM_STR);
        $statement->bindValue('last_name', $user['last_name'], PDO::PARAM_STR);
        $statement->bindValue('phone', $user['phone'], PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update user in database
     */
    public function update(array $user): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET 
        `username` = :username,
        `password` = :password,
        `email` = :email,
        `role` = :role,
        `first_name` = :first_name,
        `last_name` = :last_name,
        `phone` = :phone
    WHERE id = :id");

        $statement->bindValue('id', $user['id'], PDO::PARAM_INT);
        $statement->bindValue('username', $user['username'], PDO::PARAM_STR);
        $statement->bindValue('password', password_hash($user['password'], PASSWORD_BCRYPT), PDO::PARAM_STR);
        $statement->bindValue('email', $user['email'], PDO::PARAM_STR);
        $statement->bindValue('role', $user['role'], PDO::PARAM_STR);
        $statement->bindValue('first_name', $user['first_name'], PDO::PARAM_STR);
        $statement->bindValue('last_name', $user['last_name'], PDO::PARAM_STR);
        $statement->bindValue('phone', $user['phone'], PDO::PARAM_STR);

        return $statement->execute();
    }
}
