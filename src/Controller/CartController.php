<?php

namespace App\Controller;

use App\Model\CartManager;

class CartController extends AbstractController
{
    public function index(): string
    {
        //  SelectAll();
        $cartManager = new CartManager();
        $cart = $cartManager->selectAll('id');

        return $this->twig->render('Cart/show.html.twig', ['product' => $cart]);
    }

    public function show(int $id): string
    {
        $cartManager = new CartManager();
        $cart = $cartManager->selectOneById($id);

        return $this->twig->render('Cart/show.html.twig', ['product' => $cart]);
    }
}
