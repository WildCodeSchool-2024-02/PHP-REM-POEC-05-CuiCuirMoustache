<?php

namespace App\Model;

use PDO;

class OrderitemManager extends AbstractManager
{
    public const TABLE = 'orderitem';

    public function addProductToOrder(int $orderedId, int $productId, int $quantity, float $price)
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (ordered_id, product_id, quantity, price) 
         VALUES (:ordered_id, :product_id, :quantity, :price)");
        $statement->bindValue('ordered_id', $orderedId, PDO::PARAM_INT);
        $statement->bindValue('product_id', $productId, PDO::PARAM_INT);
        $statement->bindValue('quantity', $quantity, PDO::PARAM_INT);
        $statement->bindValue('price', $price, PDO::PARAM_STR);

        return $statement->execute();
    }
}
