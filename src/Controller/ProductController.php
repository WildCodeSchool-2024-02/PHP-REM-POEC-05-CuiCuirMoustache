<?php

namespace App\Controller;

use App\Model\CartManager;
use App\Model\ProductManager;

class ProductController extends AbstractController
{
    public function index(): string
    {
        //  Select all
        $productManager = new ProductManager();
        $products = $productManager->selectAll('id');

        return $this->twig->render('Product/index.html.twig', ['product' => $products]);
    }

    public function show(int $id): string
    {
        $productManager = new ProductManager();
        $product = $productManager->selectOneById($id);

        return $this->twig->render('Product/show.html.twig', ['product' => $product]);
    }
}
