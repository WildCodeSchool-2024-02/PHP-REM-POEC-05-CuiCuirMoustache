<?php

namespace App\Controller;

use App\Model\AuthModel;

class AuthController
{
    public function login()
    {
        // Vérifie si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupère les informations soumises dans le formulaire
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // Valide les informations d'authentification
            $authModel = new AuthModel();
            if ($authModel->authenticate($username, $password)) {
                // Authentification réussie, redirige vers une page sécurisée par exemple
                header('Location: /dashboard'); // Remplacez "/dashboard" par votre page sécurisée
                exit();
            } else {
                // Authentification échouée, redirige vers la page de login avec un message d'erreur
                header('Location: /login?error=auth_failed');
                exit();
            }
        }

        // Si aucune donnée n'a été soumise
        include_once __DIR__ . '/../View/login.php';
    }
}
