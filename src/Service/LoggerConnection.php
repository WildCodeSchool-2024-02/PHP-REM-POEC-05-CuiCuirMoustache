<?php

namespace App\Service;

use App\Service\Logger;
use PDO;

class LoggerConnection extends Logger
{
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

    public function logModification($userId)
    {
        $message = "Modification du compte utilisateur $userId.";
        $this->log($message);
    }

    public function logForgotPassword($userId)
    {
        $message = "Mot de passe oublié pour l'utilisateur $userId.";
        $this->log($message);
    }

    public function adminCreation($user, $userId)
    {
        $message = "Création de l'utilisateur $user, par $userId.";
        $this->log($message);
    }

    public function adminModify($user, $userId)
    {
        $message = "Modification de l'utilisateur' $user, par $userId.";
        $this->log($message);
    }

    public function adminDelete($user, $userId)
    {
        // pas encore fonctionnel
        $message = "Suppression de l'utilisateur' $user par $userId.";
        $this->log($message);
    }
}
