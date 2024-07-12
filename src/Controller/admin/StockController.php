<?php

namespace App\Controller\admin;

use App\Controller\AbstractController;
use App\Model\admin\StockManager;

class StockController extends AbstractController
{
    public function index(): string
    {
        //  Select all
        $stockManager = new StockManager();
        $stock = $stockManager->selectAllFromStock();

        return $this->twig->render('admin/Stock/index.html.twig', ['stock' => $stock]);
    }

    public function update(int $id)
    {
        $stockManager = new StockManager();
        $stock = $stockManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $stock = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $stockManager->updateStock($stock);
            header('Location: /admin/stock');

            // we are redirecting so we don't want any content rendered
            return null;
        }

        return $this->twig->render('admin/Stock/edit.html.twig', [
            'stock' => $stock,
        ]);
    }

    public function import()
    {
        // TODO
    }

//     function getErrorStock(array $stock): array
// {
//     $stockManager = new StockManager();
//     $errors = [];

//     if (empty($stock['name']) || strlen($stock['name']) > 255) {
//         $errors['name'] = 'Un nom est nécessaire et il ne doit pas dépasser 255 caractères.';
//     }
//     if (empty($stock['description'])) {
//         $errors['description'] = 'Une description est obligatoire.';
//     }
//     if (empty($stock['price']) || !filter_var($stock['price'], FILTER_VALIDATE_FLOAT)) {
//         $errors['price'] = 'Le prix doit être un nombre valide.';
//     }
//     if (empty($stock['category_id']) || !$stockManager->selectOneById((int)$stock['category_id'])) {
//         $errors['category_id'] = 'Category ID doit correspondre à une catégorie existante.';
//     }

//     return $errors;
// }
}
