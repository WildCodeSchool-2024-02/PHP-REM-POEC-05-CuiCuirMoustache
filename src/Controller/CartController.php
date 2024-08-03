<?php

namespace App\Controller;

use App\Service\CartService;
use App\Service\Logger;
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
        $stockManager = new StockManager();
        $cart = [];
        $totalPrice = 0;
        $totalItem = 0;
        foreach ($cartService->getCart() as $productId => $qty) {
            $product = $productManager->selectOneById($productId);
            $stock = $stockManager->getQuantityById($productId);
            $cart[] = [
                'product' => $product,
                'qty' => $qty,
                'stock' => $stock['quantity']
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

    public function add(int $id, $qty)
    {
        // Si session a un problème avec le string, vérif ici avec var_dump
        //ajoute au panier
        $cartService = new CartService();
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $errors = [];

            // verifier les entrées
            $qty = intval($qty);
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

    public function update(int $id, $qty): void
    {
        $cartService = new CartService();
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $errors = [];

            // verifier les entrées
            $qty = intval($qty);
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
        if (empty($_SESSION['user']['id'])) {
            header('Location: /login');
            exit();
        }
        $orderedId = $orderedManager->createOrder($_SESSION['user']['id'], $totalAmount, "order");

        // moins de commandes effectuer (mais moins DRY)
        $orderitemManager = new OrderitemManager();
        $stockManager = new StockManager();
        foreach ($cart as $id => $qty) {
            $product = $productManager->selectOneById($id);
            $stock = $stockManager->getStockById($id);
            $username = $_SESSION['user']['username'];
            if ($stock['quantity'] >= $qty) {
                $stockManager->updateStockFromCart($product['id'], $qty);
                $orderitemManager->addProductToOrder($orderedId, $product['id'], $qty, $product['price']);
                $this->loggerProduct->logPurchase($username, $product['name'], $qty, $product['price']);
            } else {
                header('Location: /cart?status=unavailableQuantity');
                exit();
            }
        }

        $cartService->clear();
        return $this->twig->render('Cart/ordered.html.twig', [
            'cart' => $cartToShow,
            'ordered_id' => $orderedId,
            'total_amount' => $totalAmount,
            'totalItem' => $totalItem,
        ]);
    }
}
