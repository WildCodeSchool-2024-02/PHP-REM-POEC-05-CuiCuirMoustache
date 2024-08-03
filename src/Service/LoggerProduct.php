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

    public function productCreation($product)
    {
        $message = "Création du produit $product.";
        $this->log($message);
    }

    public function productDelete($product)
    {
        // pas encore fonctionnel
        $message = "Suppression du produit $product.";
        $this->log($message);
    }

    public function productModify($product)
    {
        $message = "Modification du produit $product.";
        $this->log($message);
    }

    public function productStockModify($product)
    {
        $message = "Modification du stock du produit $product.";
        $this->log($message);
    }
}
