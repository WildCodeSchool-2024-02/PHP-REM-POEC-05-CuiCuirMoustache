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

    public function login()
    {
        return $this->twig->render('Auth/login.html.twig');
    }

    public function signup()
    {
        // Initialisez un tableau d'erreurs pour les validations de formulaire
        $errors = [];

        // Vérifiez si le formulaire a été soumis avec la méthode POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérez les données du formulaire d'inscription
            $username = $_POST['username'] ?? '';
            $first_name = $_POST['first_name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? '';
            $phone = $_POST['phone'] ?? '';

            // Validation des champs du formulaire
            if (empty($first_name)) {
                $errors[] = 'Le prénom est requis';
            }
            if (empty($last_name)) {
                $errors[] = 'Le nom est requis';
            }
            if (empty($email)) {
                $errors[] = 'L\'email est requis';
            }
            if (empty($password)) {
                $errors[] = 'Le mot de passe est requis';
            }
            if (empty($role)) {
                $errors[] = 'Le rôle est requis';
            }
            if (empty($phone)) {
                $errors[] = 'Le téléphone est requis';
            }

            // Si le formulaire est valide
            if (empty($errors)) {
                // Enregistrez l'utilisateur dans la base de données
                $success = $this->authModel->register(
                    $username,
                    $first_name,
                    $last_name,
                    $email,
                    $password,
                    $role,
                    $phone
                );
                if ($success) {
                    // Redirigez l'utilisateur après une inscription réussie
                    header('Location: /login');
                    exit();
                } else {
                    $errors[] = 'Erreur lors de l\'inscription. Veuillez réessayer.';
                }
            }
        }

        try {
            // Affichez le formulaire d'inscription avec les erreurs
            echo $this->twig->render('Auth/signup.html.twig', ['errors' => $errors]);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            echo "Erreur de rendu de template : " . $e->getMessage();
        }
    }

    private function handleLogin(): array
    {
        $errors = [];

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email)) {
            $errors[] = 'L\'email est requis pour se connecter.';
        }
        if (empty($password)) {
            $errors[] = 'Le mot de passe est requis pour se connecter.';
        }
        if (!$this->authModel->authenticate($email, $password)) {
            $errors[] = 'Email ou mot de passe incorrect.';
        }

        return $errors;
    }

    private function handleRegistration(): array
    {
        $errors = [];

        $userData = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'role' => $_POST['role'] ?? '',
            'phone' => $_POST['phone'] ?? ''
        ];

        $errors = $this->validateRegistration($userData);

        if (empty($errors)) {
            $success = $this->authModel->register(
                $userData['username'],
                $userData['first_name'],
                $userData['last_name'],
                $userData['email'],
                $userData['password'],
                $userData['role'],
                $userData['phone']
            );

            if ($success) {
                header('Location: /HomeController');
                exit();
            } else {
                $errors[] = 'Erreur lors de l\'inscription. Veuillez réessayer.';
            }
        }

        return $errors;
    }

    private function validateRegistration(array $data): array
    {
        $errors = [];

        if (empty($data['first_name'])) {
            $errors[] = 'Le prénom est requis';
        }
        if (empty($data['last_name'])) {
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

        return $errors;
    }
}
