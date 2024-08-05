<?php

namespace App\Controller\Auth;

use App\Controller\AbstractController;
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
        $this->authModel = new AuthModel();
    }

    public function authentification()
    {
        $errors = [];
        $data = ['email' => '', 'password' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data['email'] = $_POST['email'] ?? '';
            $data['password'] = $_POST['password'] ?? '';

            if (empty($data['email']) || empty($data['password'])) {
                $errors[] = 'Le mot de passe ou le mail n\'est pas correct';
            } else {
                $user = $this->authModel->authenticate($data['email'], $data['password']);

                if ($user !== null) {
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'firstname' => $user['first_name'],
                        'lastname' => $user['last_name'],
                        'email' => $user['email'],
                        'phone' => $user['phone'],
                    ];
                    // Redirection en cas de succès
                    //$this->logger->logConnection($user['username']);
                    header('Location: /');
                    exit();
                } else {
                    // Identifiants incorrects
                    $errors[] = 'Identifiants incorrects';
                }
            }
        }

        // Retournez le rendu du template avec les erreurs et les données
        return $this->twig->render(
            'Auth/login.html.twig',
            ['errors' => $errors, 'data' => $data]
        );
    }


    public function signup()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $userData = [
                'userName' => $username,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'password' => $password,
                'phone' => $phone,
            ];

            $errors = $this->validationForm($userData);

            if (empty($errors)) {
                $success = $this->authModel->register(
                    $username,
                    $firstName,
                    $lastName,
                    $email,
                    $password,
                    $phone
                );
                if ($success) {
                    //$this->logger->logCreation($userData['userName']);
                    header('Location: /');
                    exit();
                } else {
                    $errors[] = 'Erreur lors de l\'inscription. Veuillez réessayer.';
                }
            }
        }
        return $this->twig->render('Auth/signup.html.twig', ['errors' => $errors]);
    }

    public function forgotPassword()
    {
        $errors = [];
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';

            if (empty($email)) {
                $errors[] = 'L\'email est requis';
            } elseif (!$this->authModel->emailExists($email)) {
                $errors[] = 'Aucun utilisateur trouvé avec cet email';
            } else {
                $token = bin2hex(random_bytes(32));
                if ($this->authModel->storeResetToken($email, $token)) {
                    $success = true;
                } else {
                    $errors[] = 'Erreur lors de la génération du token';
                }
            }
        }

        try {
            echo $this->twig->render(
                'Auth/forgot_password.html.twig',
                ['errors' => $errors, 'success' => $success]
            );
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            echo "Erreur de rendu de template : " . $e->getMessage();
        }
    }

    public function resetPassword()
    {
        $errors = [];
        $success = false;
        $token = $_GET['token'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['password'] ?? '';

            if (empty($newPassword)) {
                $errors[] = 'Le nouveau mot de passe est requis';
            } elseif (!$this->authModel->resetPassword($token, $newPassword)) {
                $errors[] = 'Erreur lors de la réinitialisation du mot de passe';
            } else {
                $success = true;
            }
        }

        try {
            echo $this->twig->render(
                'Auth/reset_password.html.twig',
                ['errors' => $errors, 'success' => $success, 'token' => $token]
            );
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            echo "Erreur de rendu de template : " . $e->getMessage();
        }
    }

    public function renderViewWithLoginStatus($template, $params = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $isLoggedIn = isset($_SESSION['user']);
        $params['isLoggedIn'] = $isLoggedIn;

        try {
            echo $this->twig->render($template, $params);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            echo "Erreur de rendu de template : " . $e->getMessage();
        }
    }

    public function authLogout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Détruire la session
        //$this->logger->logDisconnection($_SESSION['user']['username']);
        session_destroy();

        // Redirection vers la page de login
        header('Location: /login');
        exit();
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
        if (empty($userData['phone']) || !preg_match('/^[0-9]{10}$/', $userData['phone'])) {
            $errors[] = 'Le numéro de téléphone est requis et doit être valide.';
        }
        return $errors;
    }
}
