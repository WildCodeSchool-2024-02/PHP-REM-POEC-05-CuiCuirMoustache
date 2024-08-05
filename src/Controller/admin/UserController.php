<?php

namespace App\Controller\admin;

use App\Controller\AbstractController;
use App\Model\admin\UserManager;

class UserController extends AbstractController
{
    /**
     * List User
     */
    public function index(): string
    {
        $userManager = new UserManager();
        $items = $userManager->selectAll();
        return $this->twig->render('Admin/User/index.html.twig', ['items' => $items]);
    }

    /**
     * Show informations for a specific User
     */
    public function show(int $id): string
    {
        $userManager = new UserManager();
        $item = $userManager->selectOneById($id);
        return $this->twig->render('Admin/User/show.html.twig', ['item' => $item]);
    }

    /**
     * Edit a specific User
     */
    public function edit(int $id): ?string
    {
        $userManager = new UserManager();
        $item = $userManager->selectOneById($id);
        $userId = $_SESSION['user']['username'];
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);
            $errors = getErrorForm($item);

            // if validation is ok, update and redirection
            if (empty($errors)) {
                $userManager->update($item);
                header('Location: /admin/user');
                return null;
            }
        }
        $this->loggerConnection->adminModify($item['username'], $userId);
        return $this->twig->render('Admin/User/edit.html.twig', [
            'user' => $item,
            'errors' => $errors

        ]);
    }

    /**
     * Add a new User
     */
    public function add(): ?string
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item = array_map('trim', $_POST);
            $userId = $_SESSION['user']['username'];
            $errors = getErrorForm($item);

            if (!empty($errors)) {
                return $this->twig->render('admin/User/add.html.twig', [
                    'errors' => $errors,
                    'item' => $item
                ]);
            }

            $userManager = new UserManager();
            $userManager->insert($item);
            $this->loggerConnection->adminCreation($item['username'], $userId);
            return $this->twig->render('admin/User/add.html.twig', [
                'success' => true
            ]);
        }

        return $this->twig->render('admin/User/add.html.twig', []);
    }

    /**
     * Delete a specific User
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = trim($_GET['id']);
            $userId = $_SESSION['user']['username'];
            $userManager = new UserManager();
            $user = $userManager->selectOneById((int)$id);
            $this->loggerProduct->productDelete($user['username'], $userId);
            $userManager->delete((int)$id);
            header('Location:/admin/user');
        }
    }
}
function getErrorForm(array $item): array
{
    $errors = [];

    $errors = array_merge($errors, validateUsername($item['username'] ?? ''));
    $errors = array_merge($errors, validatePassword($item['password'] ?? ''));
    $errors = array_merge($errors, validateEmail($item['email'] ?? ''));
    $errors = array_merge($errors, validateRole($item['role'] ?? ''));
    $errors = array_merge($errors, validateFirstName($item['first_name'] ?? ''));
    $errors = array_merge($errors, validateLastName($item['last_name'] ?? ''));
    $errors = array_merge($errors, validatePhone($item['phone'] ?? ''));

    return $errors;
}
function validateUsername($username): array
{
    if (empty($username) || strlen($username) > 255) {
        return ['username' => "Le nom d'utilisateur est invalide."];
    }
    return [];
}

function validatePassword($password): array
{
    if (empty($password)) {
        return ['password' => 'Le mot de passe est requis.'];
    }
    return [];
}

function validateEmail($email): array
{
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['email' => 'L\'email doit être une adresse email valide.'];
    }
    return [];
}

function validateRole($role): array
{
    $validRoles = ['admin', 'user', 'éditeur'];
    if (empty($role) || !in_array($role, $validRoles)) {
        return ['role' => 'Le rôle doit être l\'un des suivants : admin, user, éditeur.'];
    }
    return [];
}

function validateFirstName($firstName): array
{
    if (empty($firstName) || strlen($firstName) > 255) {
        return ['first_name' => 'Le prénom est invalide.'];
    }
    return [];
}

function validateLastName($lastName): array
{
    if (empty($lastName) || strlen($lastName) > 255) {
        return ['last_name' => 'Le nom de famille est invalide.'];
    }
    return [];
}

function validatePhone($phone): array
{
    if (empty($phone) || !preg_match('/^\+?\d{1,15}$/', $phone)) {
        return ['phone' => 'Le téléphone doit être un numéro valide.'];
    }
    return [];
}
