<?php

namespace App\Model\admin;

use App\Model\AbstractManager;
use PDO;

class StockManager extends AbstractManager
{
    public const TABLE = 'stock';

    public function selectAllFromStock(): array
    {
        $query = "SELECT stock.id, product_id, quantity, stock.created_at, stock.updated_at, 
        `name` FROM stock LEFT JOIN product AS p ON p.id=stock.product_id;";
        return $this->pdo->query($query)->fetchAll();
    }

    public function getStockById(int $id): array|false
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . static::TABLE . " WHERE product_id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function updateStock(array $stock): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `quantity` = :quantity WHERE id=:id");
        $statement->bindValue('id', $stock['id'], PDO::PARAM_INT);
        $statement->bindValue('quantity', $stock['quantity'], PDO::PARAM_INT);

        return $statement->execute();
    }

    public function updateStockFromCart(int $id, int $qty): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " 
        SET quantity = quantity - :quantity WHERE product_id=:id");
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->bindValue('quantity', $qty, PDO::PARAM_INT);

        return $statement->execute();
    }

    public function add()
    {
        // TODO
    }
}
