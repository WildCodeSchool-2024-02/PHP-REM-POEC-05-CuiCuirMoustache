<?php

namespace App\Controller;

use App\Model\admin\CategorieManager;
use App\Model\admin\ProductManager;

class HomeController extends AbstractController
{
    /**
     * Display home page
     */
    public function index(): string
    {

        $productManager = new ProductManager();
        $products = $productManager->selectAllStockAndCategory();
        $latestProducts = $productManager->selectThreeLatest();
        $categorieManager = new CategorieManager();
        $categories = $categorieManager->selectAll();
        return $this->twig->render('Home/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'latestProducts' => $latestProducts
        ]);
    }
}
