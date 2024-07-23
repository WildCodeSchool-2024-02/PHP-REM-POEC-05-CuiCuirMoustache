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
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);
            if (empty($item['name']) || strlen($item['name']) > 255) {
                $errors['name'] = 'Un nom est necessaire et il doit pas depasser 255 caracteres ';
            }
            if (empty($item['description'])) {
                $errors['description'] = 'une Description est obligatoire.';
            }
            if (!empty($item['parent_id']) && !filter_var($item['parent_id'], FILTER_VALIDATE_INT)) {
                $errors['parent_id'] = 'Parent ID doit etre un nombre entier.';
            }
            // if validation is ok, update and redirection

            if (empty($errors)) {
                $categorieManager->update($item);
                header('Location: /admin/categorie/show?id=' . $id);
            }

            // we are redirecting so we don't want any content rendered
            return null;
        }

        return $this->twig->render('Admin/Categorie/edit.html.twig', [
            'item' => $item,
            'errors' => $errors
        ]);
    }

    /**
     * Add a new categorie
     */
    public function add(): ?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item = array_map('trim', $_POST);
            $errors = [];
            if (empty($item['name']) || strlen($item['name']) > 255) {
                $errors['name'] = 'Un nom est necessaire et il doit pas depasser 255 caracteres ';
            }
            if (empty($item['description'])) {
                $errors['description'] = 'une description est obligatoire.';
            }
            if (!empty($item['parent_id']) && !filter_var($item['parent_id'], FILTER_VALIDATE_INT)) {
                $errors['parent_id'] = 'Parent ID doit etre un nombre entier.';
            }
            if (!empty($errors)) {
                return $this->twig->render('admin/Categorie/add.html.twig', [
                    'errors' => $errors,
                    'item' => $item
                ]);
            }
            $categorieManager = new CategorieManager();
            $categorieManager->insert($item);
            return $this->twig->render('admin/Categorie/add.html.twig', [
                'success' => true
            ]);
        }

        return $this->twig->render('admin/Categorie/add.html.twig');
    }

    /**
     * Delete a specific categorie
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = trim($_GET['id']);
            $categorieManager = new CategorieManager();
            $categorieManager->delete((int)$id);
            header('Location:/admin/categorie');
        }
    }
}
