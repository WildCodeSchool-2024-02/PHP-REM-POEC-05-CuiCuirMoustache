<?php

namespace App\Controller;

use App\Model\AuthModel;

class AuthController extends AbstractController
{
    protected AuthModel $authModel;

    public function __construct()
    {
        parent::__construct();
        $this->authModel = new AuthModel(); // Instanciate AuthModel here or use dependency injection
    }

    public function register()
    {
        // Récupérer les données du formulaire d'inscription
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $userData = [
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'role' => $_POST['role'], // Ajout du champ 'role'
                'phone' => $_POST['phone'] // Ajout du champ 'phone'
            ];

            // Validation des données
            $errors = $this->validateRegistration($userData);

            if (empty($errors)) {
                // Enregistrer l'utilisateur dans la base de données
                $success = $this->authModel->register(
                    $userData['firstname'],
                    $userData['lastname'],
                    $userData['email'],
                    $userData['password'],
                    $userData['role'],
                    $userData['phone']
                );

                if ($success) {
                    // Redirection après l'inscription réussie vers le Home page
                    header('Location: /HomeController');
                    exit();
                } else {
                    // Gestion de l'échec de l'inscription
                    $errors[] = 'Erreur lors de l\'inscription. Veuillez réessayer.';
                }
            }

            // Afficher le formulaire d'inscription avec les erreurs
            return $this->twig->render('Auth/register.html.twig', [
                'errors' => $errors,
                'data' => $userData
            ]);
        }

        // Afficher le formulaire d'inscription
        return $this->twig->render('Auth/register.html.twig');
    }

    // Validation des données d'inscription
    private function validateRegistration(array $data): array
    {
        $errors = [];

        if (empty($data['firstname'])) {
            $errors[] = 'Le prénom est requis';
        }
        if (empty($data['lastname'])) {
            $errors[] = 'Le nom est requis';
        }
        if (empty($data['email'])) {
            $errors[] = 'L\'email est requis';
        }
        if (empty($data['password'])) {
            $errors[] = 'Le mot de passe est requis';
        }
        if (empty($data['role'])) {
            $errors[] = 'Le rôle est requis';
        }
        if (empty($data['phone'])) {
            $errors[] = 'Le téléphone est requis';
        }

        // Validation supplémentaire si nécessaire

        return $errors;
    }
}
