<?php

namespace App\Model;

use PDO;

class OrderedManager extends AbstractManager
{
    public const TABLE = 'ordered';

    public function createOrder(int $userId, float $totalAmount, string $status)
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (user_id, total_amount, status) 
         VALUES (:user_id, :total_amount, :status)");
        $statement->bindValue('user_id', $userId, PDO::PARAM_INT);
        $statement->bindValue('total_amount', $totalAmount, PDO::PARAM_STR);
        $statement->bindValue('status', $status, PDO::PARAM_STR);

        $statement->execute();
        return $this->pdo->lastInsertId();
    }
}
