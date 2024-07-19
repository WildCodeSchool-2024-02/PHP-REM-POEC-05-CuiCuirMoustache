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
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $userData = [
                'userName' => $username,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'password' => $password,
                'role' => $role,
                'phone' => $phone,
            ];

            // Validation des champs du formulaire
            $errors = $this->validationForm($userData);

            // Si le formulaire est valide
            if (empty($errors)) {
                // Enregistrez l'utilisateur dans la base de données
                $success = $this->authModel->register(
                    $username,
                    $firstName,
                    $lastName,
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
    private function validationForm(array $userData): array
    {
        $errors = [];
        if (empty($userData['firstName'])) {
            $errors[] = 'Le prénom est requis';
        }
        if (empty($userData['lastName'])) {
            $errors[] = 'Le nom est requis';
        }
        if (empty($userData['email'])) {
            $errors[] = 'L\'email est requis';
        }
        if (empty($userData['password'])) {
            $errors[] = 'Le mot de passe est requis';
        }
        if (empty($userData['role'])) {
            $errors[] = 'Le rôle est requis';
        }
        if (empty($userData['phone'])) {
            $errors[] = 'Le téléphone est requis';
        }
        return $errors;
    }
}
