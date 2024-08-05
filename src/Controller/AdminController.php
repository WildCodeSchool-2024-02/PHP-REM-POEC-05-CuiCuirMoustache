<?php

namespace App\Controller;

use App\Model\admin\ProductManager;

class AdminController extends AbstractController
{
    /**
     * Display home admin page
     */
    public function index(): string
    {
        $productManager = new ProductManager();
        $products = $productManager->selectAllStockAndCategory();

        return $this->twig->render('Admin/index.html.twig', ['product' => $products]);
    }
}
