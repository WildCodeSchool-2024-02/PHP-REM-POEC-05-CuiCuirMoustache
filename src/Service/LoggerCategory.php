<?php

namespace App\Service;

use App\Service\Logger;
use PDO;

class LoggerCategory extends Logger
{
    public function categoryCreation($category, $userId)
    {
        $message = "Création de la catégorie $category, par $userId.";
        $this->log($message);
    }

    public function categoryModify($category, $userId)
    {
        $message = "Modification de la catégorie $category, par $userId.";
        $this->log($message);
    }

    public function categoryDelete($category, $userId)
    {
        // pas encore fonctionnel
        $message = "Suppression de la catégorie $category par $userId.";
        $this->log($message);
    }
}
