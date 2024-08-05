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

    public function update(int $id): string
    {
        $stockManager = new StockManager();
        $item = $stockManager->selectAllFromStockById($id);
        $stock = $stockManager->selectOneById($id);
        $userId = $_SESSION['user']['username'];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $stock = array_map('trim', $_POST);

            // validations
            if (!filter_var($stock['quantity'], FILTER_VALIDATE_FLOAT) && ($stock['quantity'] != 0)) {
                $errors['quantity'] = 'La quantité doit être un nombre valide.';
            }

            if ($stock['quantity'] < 0) {
                $errors['quantity2'] = 'Le prix doit être supérieure à 0.';
            }

            // if validation is ok, update and redirection
            if (empty($errors)) {
                $stockManager->updateStock($stock);
            }

            // we are redirecting so we don't want any content rendered
            $this->loggerProduct->productStockModify($item['name'], $userId);
            header('Location: /admin/stock');
        }

        return $this->twig->render('Admin/Stock/index.html.twig', [
            'stock' => $stock,
            'errors' => $errors
        ]);
    }

    public function import()
    {
        // TODO
    }
}
