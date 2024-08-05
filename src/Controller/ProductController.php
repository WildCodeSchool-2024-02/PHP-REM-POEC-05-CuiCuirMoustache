<?php

namespace App\Controller;

use App\Model\admin\CategorieManager;
use App\Model\admin\ProductManager as AdminProductManager;
use App\Model\CartManager;
use App\Model\ProductManager;

class ProductController extends AbstractController
{
    public function index(): string
    {
        $productManager = new AdminProductManager();
        $products = $productManager->selectAllStockAndCategory();
        $categorieManager = new CategorieManager();
        $categories = $categorieManager->selectAll();
        return $this->twig->render('Product/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function show(int $id): string
    {
        $productManager = new ProductManager();
        $product = $productManager->selectOneById($id);

        return $this->twig->render('Product/show.html.twig', ['product' => $product]);
    }
}
