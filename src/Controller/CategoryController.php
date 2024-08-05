<?php

namespace App\Controller;

use App\Model\ProductManager;
use App\Model\admin\CategorieManager;

class CategoryController extends AbstractController
{
    public function index(int $id): string
    {
        $errors = [];
        // @phpstan-ignore-next-line
        if (is_null(filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE))) {
            $errors[] = "Veuillez selectionner une catégorie valide";
        }
        $categoryManager = new CategorieManager();
        $category = $categoryManager->selectOneById($id);
        if (empty($category)) {
            $errors[] = "Cette catégorie n'existe pas";
            $products = [];
            $categoryName = 'Non trouvée';
            $description =  '';
        } else {
            $categoryName = $category['name'];
            $description = $category['description'];
            $productsManager = new ProductManager();
            $products = $productsManager->getProductByCategory($id);
        }
        return $this->twig->render('Category/index.html.twig', [
            'errors' => $errors,
            'categoryName' => $categoryName,
            'products' => $products,
            'description' => $description,
        ]);
    }
}
