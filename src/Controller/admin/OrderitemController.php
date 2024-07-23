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
