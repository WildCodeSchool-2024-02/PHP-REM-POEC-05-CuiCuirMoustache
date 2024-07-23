<?php

namespace App\Model\admin;

use App\Model\AbstractManager;
use PDO;

class ProductManager extends AbstractManager
{
    public const TABLE = 'product';

    // Select All + stock
    public function selectAllAndStock(): array
    {
        $query = "SELECT product.*, quantity FROM product INNER JOIN stock ON product_id=product.id;";
        return $this->pdo->query($query)->fetchAll();
    }

    // Select All + category
    public function selectAllStockAndCategory(): array
    {
        $query = "SELECT product.*, stock.*, category.name AS category_name 
        FROM product INNER JOIN category ON category_id=category.id INNER JOIN stock ON product_id=product.id;";
        return $this->pdo->query($query)->fetchAll();
    }

    /**
     * Insert new product in database
     */
    public function insert(array $product): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (
        `name`,
         `description`,
          `price`,
           `category_id`)
         VALUES (:name, :description, :price, :category_id)");
        $statement->bindValue('name', $product['name'], PDO::PARAM_STR);
        $statement->bindValue('description', $product['description'], PDO::PARAM_STR);
        $statement->bindValue('price', $product['price'], PDO::PARAM_STR);
        $statement->bindValue('category_id', $product['category_id'], PDO::PARAM_INT);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update category in database
     */
    public function update(array $product): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `name` = :name,
         `description` = :description,
          `price` = :price,
           `category_id` = :category_id
         WHERE id = :id");
        $statement->bindValue('id', $product['id'], PDO::PARAM_INT);
        $statement->bindValue('name', $product['name'], PDO::PARAM_STR);
        $statement->bindValue('description', $product['description'], PDO::PARAM_STR);
        $statement->bindValue('price', $product['price'], PDO::PARAM_STR);
        $statement->bindValue('category_id', $product['category_id'], PDO::PARAM_INT);

        return $statement->execute();
    }
}
