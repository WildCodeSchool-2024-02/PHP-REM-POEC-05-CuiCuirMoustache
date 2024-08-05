<?php

namespace App\Model\admin;

use App\Model\AbstractManager;
use PDO;

class CategorieManager extends AbstractManager
{
    public const TABLE = 'category';

    /**
     * Insert new category in database
     */
    public function insert(array $category): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (
        `name`,
         `description`,
          `parent_id`,
          image)
         VALUES (:name, :description, :parent_id, :image)");
        $statement->bindValue('name', $category['name'], PDO::PARAM_STR);
        $statement->bindValue('description', $category['description'], PDO::PARAM_STR);
        if (empty($category['parent_id'])) {
            $statement->bindValue(':parent_id', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':parent_id', $category['parent_id'], PDO::PARAM_INT);
        }
        $statement->bindValue('image', $category['image'], PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update category in database
     */
    public function update(array $category): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `name` = :name,
         `description` = :description,
          `parent_id` = :parent_id,
             `image` = :image
              WHERE id = :id");
        $statement->bindValue('id', $category['id'], PDO::PARAM_INT);
        $statement->bindValue('name', $category['name'], PDO::PARAM_STR);
        $statement->bindValue('description', $category['description'], PDO::PARAM_STR);
        if (empty($category['parent_id'])) {
            $statement->bindValue(':parent_id', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':parent_id', $category['parent_id'], PDO::PARAM_INT);
        }
        $statement->bindValue('image', $category['image'], PDO::PARAM_STR);

        return $statement->execute();
    }
}
