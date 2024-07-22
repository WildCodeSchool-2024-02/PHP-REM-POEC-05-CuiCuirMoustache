<?php

namespace App\Controller\admin;

use App\Controller\AbstractController;
use App\Model\OrderitemManager;

class OrderitemController extends AbstractController
{
    public function index(): string
    {
        //  Select all
        $orderitemManager = new OrderitemManager();
        $orders = $orderitemManager->selectAllOrderedInfo();

        return $this->twig->render('admin/Orderitem/index.html.twig', ['order' => $orders]);
    }

    public function show(int $id): string
    {
        $orderitemManager = new OrderitemManager();
        $order = $orderitemManager->selectOneById($id);
        return $this->twig->render('Admin/Orderitem/show.html.twig', ['order' => $order]);
    }
}
