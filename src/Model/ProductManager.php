<?php

namespace App\Model;

use PDO;

class ProductManager extends AbstractManager
{
    public const TABLE = 'product';

    public function getProductByCategory(int $categoryId): array
    {
        $query = "SELECT p.name as productName, p.id as productid, p.price,
         p.description, p.updated_at ,p.image, p.descriptionDetail,
          c.name as categoryName FROM product p INNER JOIN category c
          ON p.category_id=c.id 
          WHERE c.id=:id";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(':id', $categoryId, PDO::PARAM_INT);
        $stm->execute();
        return $stm->fetchAll();
    }
}
