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
        $stock = $stockManager->selectAll('id');

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
}
