<?php

namespace App\Controller;

use App\Service\CartService;
use App\Model\admin\StockManager;
use App\Model\OrderedManager;
use App\Model\OrderitemManager;
use App\Model\ProductManager;

class CartController extends AbstractController
{
    public function index(string $status = ""): string
    {
        $productManager = new ProductManager();
        $cartService = new CartService();
        $cart = [];
        $totalPrice = 0;
        $totalItem = 0;
        foreach ($cartService->getCart() as $productId => $qty) {
            $product = $productManager->selectOneById($productId);
            $cart[] = [
                'product' => $product,
                'qty' => $qty
            ];
            $totalPrice += $product['price'] * $qty;
            $totalItem += $qty;
        }


        return $this->twig->render('Cart/index.html.twig', [
            'cart' => $cart,
            'total' => $totalPrice,
            'status' => $status,
            'totalItem' => $totalItem
        ]);
    }

    public function add(int $id, int $qty)
    {
        // Si session a un problème avec le string, vérif ici avec var_dump
        //ajoute au panier
        $cartService = new CartService();
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $errors = [];
            // verifier les entrées
            if ($qty <= 0) {
                $errors['qty'] = 'Une quantité doit toujours être supérieure à 0.';
            }

            // les données sont ok
            if (empty($errors)) {
                $cartService->addProduct($id, $qty);
                header('Location: /cart?status=added');
            } else {
                header('Location: /product/show?id=' . $id);
            }
        }
    }

    public function update(int $id, int $qty)
    {
        $cartService = new CartService();
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $errors = [];
            // verifier les entrées
            if ($qty <= 0) {
                $errors['qty'] = 'Une quantité doit toujours être supérieure à 0.';
            }

            // les données sont ok
            if (empty($errors)) {
                $cartService->updateProduct($id, $qty);
                header('Location: /cart?status=updated');
            } else {
                header('Location: /cart?status=falseQuantity');
            }
        }
    }

    public function delete(int $id): void
    {
        $cartService = new CartService();
        $cartService->deleteProduct($id);

        header('Location: /cart?status=deleted');
    }

    public function order(): string
    {
        $cartService = new CartService();
        $cart = $cartService->getCart();
        if (count($cart) == 0) {
            header('Location: /');
        }

        $productManager = new ProductManager();

        $totalAmount = 0;
        $totalItem = 0;
        $cartToShow = [];
        foreach ($cart as $id => $qty) {
            if ($qty > 0) {
                $product = $productManager->selectOneById($id);
                $totalAmount += $qty * $product['price'];
                $totalItem += $qty;
                $cartToShow[] = [
                    'product' => $product,
                    'qty' => $qty
                ];
            } else {
                header('Location: /cart?status=modify');
            }
        }

        $orderedManager = new OrderedManager();

        // createOrder(1, ...) est le user que j'ai crée directement dans la bdd,
        // il faudra le remplacer par une variable
        $orderedId = $orderedManager->createOrder(1, $totalAmount, "order");

        // moins de commandes effectuer (mais moins DRY)
        $orderitemManager = new OrderitemManager();
        $stockManager = new StockManager();
        foreach ($cart as $id => $qty) {
            $product = $productManager->selectOneById($id);
            $orderitemManager->addProductToOrder($orderedId, $product['id'], $qty, $product['price']);
            $stock = $stockManager->getStockById($id);
            if ($stock['quantity'] >= $qty) {
                $stockManager->updateStockFromCart($product['id'], $qty);
            } else {
                header('Location: /cart?status=falseQuantity');
            }
        }

        $cartService->clear();
        return $this->twig->render('Cart/ordered.html.twig', [
            'cart' => $cartToShow,
            'ordered_id' => $orderedId,
            'total_amount' => $totalAmount,
            'totalItem' => $totalItem
        ]);
    }
}
