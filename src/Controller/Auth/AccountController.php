<?php

namespace App\Controller\Auth;

use App\Controller\AbstractController;
use App\Model\AuthModel;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AccountController extends AbstractController
{
    protected AuthModel $authModel;

    public function __construct()
    {
        parent::__construct();
        $this->authModel = new AuthModel();
    }

    public function account()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        try {
            echo $this->twig->render('Auth/user_account.html.twig', [
                'user' => $_SESSION['user']
            ]);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            echo "Erreur de rendu de template : " . $e->getMessage();
        }
    }

    public function editAccount()
    {
        // Démarrer la session si elle n'est pas déjà démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $user = $_SESSION['user'];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getPostData($user);

            // Traiter la mise à jour de l'utilisateur
            $this->processUserUpdate($data, $user, $errors);
        }

        $this->renderView('Auth/edit_account.html.twig', [
            'user' => $user,
            'errors' => $errors
        ]);
    }

    private function getPostData(array $user): array
    {
        return [
            'username' => $_POST['username'] ?? $user['username'],
            'email' => $_POST['email'] ?? $user['email'],
            'firstname' => $_POST['firstname'] ?? $user['firstname'],
            'lastname' => $_POST['lastname'] ?? $user['lastname'],
            'phone' => $_POST['phone'] ?? $user['phone'],
        ];
    }

    private function validateData(array $data): bool
    {
        return !in_array('', $data, true);
    }

    private function processUserUpdate(array $data, array $user, array &$errors)
    {
        if ($this->validateData($data)) {
            if ($this->updateUser($user['id'], $data)) {
                $_SESSION['user'] = array_merge($user, $data);
                $this->redirect('/account');
            } else {
                $errors[] = 'Erreur lors de la mise à jour. Veuillez réessayer.';
            }
        } else {
            $errors[] = 'Tous les champs doivent être remplis.';
        }
    }

    private function updateUser(int $userId, array $data): bool
    {
        return $this->authModel->updateUser($userId, $data);
    }

    private function redirect(string $url)
    {
        header("Location: $url");
        exit();
    }

    private function renderView(string $template, array $params)
    {
        try {
            echo $this->twig->render($template, $params);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            echo "Erreur de rendu de template : " . $e->getMessage();
        }
    }
}
