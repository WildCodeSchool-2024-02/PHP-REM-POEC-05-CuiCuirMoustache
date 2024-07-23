<?php

namespace App\Controller\admin;

use App\Controller\AbstractController;
use App\Model\OrderedManager;
use App\Model\OrderitemManager;

class OrderitemController extends AbstractController
{
    public function index(): string
    {
        //  Select all
        $orderitemManager = new OrderitemManager();
        $orders = $orderitemManager->selectAllOrderedInfo();

        return $this->twig->render('admin/Orderitem/index.html.twig', ['orders' => $orders]);
    }

    public function show(int $id): string
    {
        $orderitemManager = new OrderitemManager();
        $order = $orderitemManager->getAllOrderedInfoById($id);
        return $this->twig->render('Admin/Orderitem/show.html.twig', ['order' => $order]);
    }

    // public function edit(int $id): ?string
    // {
    //     // -- $productManager = new ProductManager();
    //     // -- $categoryManager = new CategorieManager();
    //     // -- $stockManager = new StockManager();
    //     $orderitemManager = new OrderitemManager();
    //     // -- $categories = $categoryManager->selectAll();
    //     // -- $item = $productManager->selectOneById($id);
    //     // -- $stock = $stockManager->getStockById($id);
    //     $orders = $orderitemManager->getAllOrderedInfoById($id);
    //     $errors = [];
    //     $errorsTwo = [];
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         // -- clean $_POST data
    //         // -- $item = array_map('trim', $_POST);
    //         // -- $stock = array_map('trim', $_POST);
    //         $orders = array_map('trim', $_POST);
    //         var_dump($orders);
    //         die();
    //         // -- $item['id'] = (int)$item['id'];
    //         // -- $errors = getErrorForm($item);
    //         // -- $errorsTwo = getErrorFormQuantity($item);

    //         // if validation is ok, update and redirection
    //         if (empty($errors) && empty($errorsTwo)) {
    //             // -- $productManager->update($item);
    //             // -- $stockManager->updateStock($stock);
    //             header('Location: /admin/product/show?id=' . $id);
    //             return null;
    //         }
    //     }

    //     return $this->twig->render('Admin/Product/edit.html.twig', [
    //         // -- 'item' => $item,
    //         'errors' => $errors,
    //         'errorsTwo' => $errorsTwo,
    //         // -- 'categories' => $categories,
    //         // -- 'stock' => $stock
    //     ]);
    // }
}
function getErrorForm(array $item): array
{
    $errors = [];

    if (empty($item['name']) || strlen($item['name']) > 255) {
        $errors['name'] = 'Un nom est nécessaire et il ne doit pas dépasser 255 caractères.';
    }
    if (empty($item['description'])) {
        $errors['description'] = 'Une description est obligatoire.';
    }
    if (empty($item['price']) || !filter_var($item['price'], FILTER_VALIDATE_FLOAT) || $item['price'] < 0) {
        $errors['price'] = 'Le prix doit être un nombre valide.';
    }
    return $errors;
}
