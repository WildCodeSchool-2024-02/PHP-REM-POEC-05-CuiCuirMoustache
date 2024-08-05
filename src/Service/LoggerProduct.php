<?php

namespace App\Service;

use App\Service\Logger;
use PDO;

class LoggerProduct extends Logger
{
    public function logPurchase($userId, $item, $qty, $amount)
    {
        $message = "Utilisateur $userId a acheté $item * $qty pour $amount €.";
        $this->log($message);
    }

    public function productCreation($product, $userId)
    {
        $message = "Création du produit $product par $userId.";
        $this->log($message);
    }

    public function productDelete($product, $userId)
    {
        $message = "Suppression du produit $product par $userId.";
        $this->log($message);
    }

    public function productModify($product, $userId)
    {
        $message = "Modification du produit $product par $userId.";
        $this->log($message);
    }

    public function productStockModify($product, $userId)
    {
        $message = "Modification du stock du produit $product par $userId.";
        $this->log($message);
    }

    public function orderModify($orderDate, $user, $userId)
    {
        $message = "Modification de la commande du $orderDate de $user par $userId.";
        $this->log($message);
    }
}
