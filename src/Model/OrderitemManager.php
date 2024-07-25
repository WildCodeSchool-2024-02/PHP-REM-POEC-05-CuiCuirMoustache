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

    public function selectAllOrderedInfo(): array
    {
        $query = "SELECT o.id, username, total_amount, o.created_at, ordered_id
        FROM " . self::TABLE . " AS oi
        INNER JOIN ordered AS o ON ordered_id=o.id
        INNER JOIN product AS p ON product_id=p.id
        INNER JOIN `user` AS u ON user_id=u.id
        GROUP BY ordered_id
        ORDER BY ordered_id;";
        return $this->pdo->query($query)->fetchAll();
    }

    public function getAllOrderedInfoById(int $id): array|false
    {
        $statement = $this->pdo->prepare("SELECT orderitem.id as ordered_id, 
        total_amount, username, ordered.created_at, `name`, orderitem.price, quantity, ordered.id
        FROM ordered
        INNER JOIN user ON user.id=user_id
        INNER JOIN orderitem ON ordered.id=ordered_id
        INNER JOIN product ON product.id=product_id
        HAVING ordered.id = :id
        ORDER BY ordered.created_at;");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function update(array $order): bool
    {
        $statement = $this->pdo->prepare("UPDATE "  . self::TABLE . " SET quantity = :quantity, price = :price
        WHERE id=:id;");
        $statement->bindValue('quantity', $order['quantity'], PDO::PARAM_INT);
        $statement->bindValue('price', $order['price'], PDO::PARAM_INT);
        $statement->bindValue('id', $order['id'], PDO::PARAM_INT);

        return $statement->execute();
    }
}
