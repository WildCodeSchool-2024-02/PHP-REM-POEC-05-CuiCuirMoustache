<?php

namespace App\Model;

use PDO;

class CartManager extends AbstractManager
{
    public const TABLE = 'product';

    /**
     * Insert new item in database
     */
    public function insert(array $cart): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`name`) VALUES (:name)");
        $statement->bindValue('name', $cart['name'], PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update item in database
     */
    // public function update(array $cart): bool
    // {
    //     $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `name` = :name WHERE id=:id");
    //     $statement->bindValue('id', $cart['id'], PDO::PARAM_INT);
    //     $statement->bindValue('name', $cart['name'], PDO::PARAM_STR);

    //     return $statement->execute();
    // }
}
