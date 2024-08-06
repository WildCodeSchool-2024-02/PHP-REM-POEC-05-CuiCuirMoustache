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
        $categoriesMenu = [];
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
            $productsCategory = $this->getProducts($id);
            //Get subcategories
            $subProducts = $this->getSubCategoryProducts($id);
            //Merge subproducts with products
            $products = array_merge($subProducts, $productsCategory);
            // Get all categories menu (for breadcrumb)
            $categoriesMenu = $categoryManager->selectAll();
        }
        return $this->twig->render('Category/index.html.twig', [
            'errors' => $errors,
            'categoryName' => $categoryName,
            'products' => $products,
            'description' => $description,
            'categories' => $categoriesMenu
        ]);
    }

    private function getSubCategoryProducts(int $categoryId): array
    {
        $subProducts = [];
        $categorieManager = new CategorieManager();
        $subCategories = $categorieManager->getSubCategories($categoryId);
        foreach ($subCategories as $subCategory) {
            $subProducts = array_merge($subProducts, $this->getProducts($subCategory['id']));
        }
        return $subProducts;
    }

    private function getProducts(int $category): array
    {
        $productsManager = new ProductManager();
        $products = $productsManager->getProductByCategory($category);
        return $products;
    }
}
