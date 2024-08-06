<?php

namespace App\Trait;

use App\Model\admin\CategorieManager;

trait CategoriesGetter
{
    private function getCategories(): array
    {
        $categoryManager = new CategorieManager();
        return $categoryManager->getCategories();
    }
}
