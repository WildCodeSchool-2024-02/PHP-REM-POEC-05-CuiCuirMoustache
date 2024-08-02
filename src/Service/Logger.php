<?php

namespace App\Service;

use PDO;

class Logger
{
    private $logFile;

    public function __construct($fileName)
    {
        $this->logFile = $fileName;
        // Crée le fichier log s'il n'existe pas déjà
        if (!file_exists($this->logFile)) {
            file_put_contents($this->logFile, "");
        }
    }

    public function log($message)
    {
        date_default_timezone_set("Europe/Paris");
        $timeStamp = date("Y-m-d H:i:s");
        $formattedMessage = "[" . $timeStamp . "] " . $message . PHP_EOL;
        file_put_contents($this->logFile, $formattedMessage, FILE_APPEND);
    }

    public function logConnection($userId)
    {
        $message = "Utilisateur $userId connecté.";
        $this->log($message);
    }

    public function logDisconnection($userId)
    {
        $message = "Utilisateur $userId déconnecté.";
        $this->log($message);
    }

    public function logCreation($userId)
    {
        $message = "Création du compte utilisateur $userId.";
        $this->log($message);
    }

    public function logPurchase($userId, $item, $qty, $amount)
    {
        $message = "Utilisateur ID $userId a acheté $item * $qty pour $amount €.";
        $this->log($message);
    }

    public function productCreation($product)
    {
        $message = "Création du produit $product.";
        $this->log($message);
    }
}
