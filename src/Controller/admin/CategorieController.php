<?php

namespace App\Controller\admin;

use App\Controller\AbstractController;
use App\Model\admin\CategorieManager;

class CategorieController extends AbstractController
{
    /**
     * List categorie
     */
    public function index(): string
    {
        $categorieManager = new CategorieManager();
        $items = $categorieManager->selectAll();
        return $this->twig->render('Admin/Categorie/index.html.twig', ['items' => $items]);
    }

    /**
     * Show informations for a specific categorie
     */
    public function show(int $id): string
    {
        $categorieManager = new CategorieManager();
        $item = $categorieManager->selectOneById($id);
        return $this->twig->render('Admin/Categorie/show.html.twig', ['item' => $item]);
    }

    /**
     * Edit a specific categorie
     */
    public function edit(int $id): ?string
    {
        $categorieManager = new CategorieManager();
        $item = $categorieManager->selectOneById($id);
        $categories = $categorieManager->selectAll();
        $userId = $_SESSION['user']['username'];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item = array_map('trim', $_POST);

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $basedir = __DIR__ . '/../../../public';
                $uploadDir = '/assets/images/uploads/';
                $uploadFile = $basedir . $uploadDir . basename($_FILES['image']['name']);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    $item['image'] = basename($_FILES['image']['name']);
                } else {
                    $errors['image'] = 'Erreur lors du téléchargement de l\'image.';
                }
            }

            $errors = array_merge($errors, getErrorForm($item));

            if (empty($errors)) {
                $categorieManager->update($item);
                $this->loggerCategory->categoryModify($item['name'], $userId);
                header('Location: /admin/categorie/show?id=' . $id);
            }

            // we are redirecting so we don't want any content rendered
            return null;
        }

        return $this->twig->render('Admin/Categorie/edit.html.twig', [
            'item' => $item,
            'errors' => $errors,
            'categories' => $categories,
        ]);
    }

    /**
     * Add a new categorie
     */
    public function add(): ?string
    {
        $categorieManager = new CategorieManager();
        $categories = $categorieManager->selectAll();
        $userId = $_SESSION['user']['username'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item = array_map('trim', $_POST);
            $errors = [];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $basedir = __DIR__ . '/../../../public';
                $uploadDir = '/assets/images/uploads/';
                $uploadFile = $basedir . $uploadDir . basename($_FILES['image']['name']);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    $item['image'] = basename($_FILES['image']['name']);
                } else {
                    $errors['image'] = 'Erreur lors du téléchargement de l\'image.';
                }
            }

            $errors = array_merge($errors, getErrorForm($item));

            if (!empty($errors)) {
                return $this->twig->render('admin/Categorie/add.html.twig', [
                    'errors' => $errors,
                    'item' => $item,
                    'categories' => $categories
                ]);
            }

            $categorieManager->insert($item);
            $this->loggerCategory->categoryCreation($item['name'], $userId);
            return $this->twig->render('admin/Categorie/add.html.twig', [
                'success' => true,
                'categories' => $categories
            ]);
        }

        return $this->twig->render('admin/Categorie/add.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * Delete a specific categorie
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = trim($_GET['id']);
            $userId = $_SESSION['user']['username'];
            $categorieManager = new CategorieManager();
            $category = $categorieManager->selectOneById((int)$id);
            $this->loggerCategory->categoryDelete($category['name'], $userId);
            $categorieManager->delete((int)$id);
            header('Location:/admin/categorie');
        }
    }
}

function getErrorForm(array $item): array
{
    $errors = [];

    $errors = array_merge($errors, validateCategoryname($item['name'] ?? ''));
    $errors = array_merge($errors, validateCategorydescription($item['description'] ?? ''));
    $errors = array_merge($errors, validateCategoryimage($item['image'] ?? ''));
    $errors = array_merge($errors, validateCategorycategory($item['parent_id'] ?? ''));

    return $errors;
}
function validateCategoryname($name): array
{
    if (empty($name) || strlen($name) > 255) {
        return ['name' => "Un nom est nécessaire et il ne doit pas dépasser 255 caractères."];
    }
    return [];
}

function validateCategorydescription($description): array
{
    if (empty($description)) {
        return ['description' => "Une description est obligatoire."];
    }
    return [];
}

function validateCategoryimage($image): array
{
    if (empty($image) || !preg_match('/\.(jpg|jpeg|png|gif)$/i', $image)) {
        return ['image' => "Le format de l\'image doit être jpg, jpeg, png ou gif."];
    }

    return [];
}

function validateCategorycategory($parent): array
{
    if (!empty($parent) && !filter_var($parent, FILTER_VALIDATE_INT)) {
        return ['parent_id' => "Parent ID doit etre un nombre entier."];
    }
    return [];
}
