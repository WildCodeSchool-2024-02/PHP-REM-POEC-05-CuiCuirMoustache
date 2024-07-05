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
}
