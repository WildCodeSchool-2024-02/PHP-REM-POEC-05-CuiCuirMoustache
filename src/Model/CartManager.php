<?php

namespace App\Model;

use PDO;

class CartManager
{
    public function __construct()
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function getCart()
    {
        return $_SESSION['cart'];
    }

    public function addProduct($productId, $qty)
    {
        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = 0;
        }

        $_SESSION['cart'][$productId] += $qty;
    }

    public function deleteProduct($productId)
    {
        unset($_SESSION['cart'][$productId]);
    }

    public function updateProduct($productId, $qty)
    {
        $_SESSION['cart'][$productId] = $qty;
    }

    public function clear()
    {
        $_SESSION['cart'] = [];
    }
}
