<?php

namespace App\Model\admin;

use App\Model\AbstractManager;
use PDO;

class StockManager extends AbstractManager
{
    public const TABLE = 'stock';

    public function updateStock(array $stock): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `quantity` = :quantity WHERE id=:id");
        $statement->bindValue('id', $stock['id'], PDO::PARAM_INT);
        $statement->bindValue('quantity', $stock['quantity'], PDO::PARAM_INT);

        return $statement->execute();
    }

    // public function idToName(array $stock): bool
    // {
    // $statement = $this->pdo->prepare("SELECT `name` " . self::TABLE . "
    //  LEFT JOIN product AS p ON p.id=s.product_id;")
    // }
}
