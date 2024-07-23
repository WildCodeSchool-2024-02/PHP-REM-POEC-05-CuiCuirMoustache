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
        $totalAmount = 0;
        foreach ($order as $ord) {
            $totalAmount += $ord['price'] * $ord['quantity'];
        }
        return $this->twig->render('Admin/Orderitem/show.html.twig', [
            'order' => $order,
            'totalAmount' => $totalAmount
        ]);
    }

    public function edit($id): ?string
    {
        $orderitemManager = new OrderitemManager();
        $order = $orderitemManager->selectOneById((int)$id);
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $order = array_map('trim', $_POST);
            $order['price'] = (int)$order['price'];
            $order['quantity'] = (int)$order['quantity'];
            $errors = getErrorForm($order);

            // if validation is ok
            if (empty($errors)) {
                $orderitemManager->update($order);
                header('Location: /admin/orderitem');
            }
        }

        return $this->twig->render('Admin/Orderitem/edit.html.twig', [
            'order' => $order,
            'errors' => $errors,
        ]);
    }
}
function getErrorForm(array $order): array
{
    $errors = [];

    if (empty($order['price']) || $order['price'] < 0) {
        $errors['price'] = 'Le prix doit être un nombre valide.';
    }

    if (empty($order['quantity']) || $order['quantity'] < 0) {
        $errors['quantity'] = 'La quantité doit être supérieure à 0.';
    }

    return $errors;
}
