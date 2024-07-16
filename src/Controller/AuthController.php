<?php

namespace App\Controller;

use App\Model\AuthModel;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AuthController extends AbstractController
{
    protected AuthModel $authModel;

    public function __construct()
    {
        parent::__construct();
        $this->authModel = new AuthModel(); // Instanciate AuthModel here or use dependency injection
    }

    public function loginOrRegister()
    {
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            if (isset($_POST['login'])) {
                // Soumission du formulaire de connexion
                $errors = $this->handleLogin();
            } elseif (isset($_POST['signup'])) {
                // Soumission du formulaire d'inscription
                $errors = $this->handleRegistration();
            }
        }
        try {
            // Afficher le formulaire de login/inscription
            echo $this->twig->render('Auth/login.html.twig', ['errors' => $errors]);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            // Gérer l'erreur de rendu Twig
            echo "Erreur de rendu de template : " . $e->getMessage();
        }
    }

    private function handleLogin(): array
    {
        $errors = [];

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validation des champs
        if (empty($email)) {
            $errors[] = 'L\'email est requis pour se connecter.';
        }

        if (empty($password)) {
            $errors[] = 'Le mot de passe est requis pour se connecter.';
        }

        // Si les données sont valides
        if (!$this->authModel->authenticate($email, $password)) {
             $errors[] = 'Email ou mot de passe incorrect.';
        }

        return $errors;
    }

    private function handleRegistration(): array
    {
        $errors = [];

        $userData = [
            'firstname' => $_POST['firstname'] ?? '',
            'lastname' => $_POST['lastname'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'role' => $_POST['role'] ?? '',
            'phone' => $_POST['phone'] ?? ''
        ];

        // Validation des données d'inscription (vous pouvez implémenter une méthode de validation ici)

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
                // Redirection après l'inscription réussie
                header('Location: /HomeController');
                exit();
            } else {
                // Gestion de l'échec de l'inscription
                $errors[] = 'Erreur lors de l\'inscription. Veuillez réessayer.';
            }
        }

        return $errors;
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
