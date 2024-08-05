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

    public function getCategories(): array
    {
        $categories = $this->selectAllParentCategories();

        $categoriesArray = [];
        foreach ($categories as $category) {
            $subCategories = $this->getSubCategories($category['id']);
            $categoryInfo = [
                'id' => $category['id'],
                'name' => $category['name'],
                'image' => $category['image'],
                'subcategories' => $subCategories
            ];
            $categoriesArray[] = $categoryInfo;
        }
        return  $categoriesArray;
    }

    private function selectAllParentCategories(): array
    {
        $query = "SELECT * FROM category WHERE parent_id is null";
        $stm = $this->pdo->query($query);
        return $stm->fetchAll();
    }

    private function getSubCategories(int $category): array
    {
        $query = "SELECT id, name, image FROM category WHERE parent_id = $category";
        $stm = $this->pdo->query($query);
        $result = $stm->fetchAll();
        return $result;
    }
}
