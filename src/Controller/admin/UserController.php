<?php

namespace App\Controller\admin;

use App\Controller\AbstractController;
use App\Model\admin\UserManager;

class UserController extends AbstractController
{
    public function index(): string
    {
        //  Select all
        $userManager = new UserManager();
        $user = $userManager->selectAll();

        return $this->twig->render('admin/User/index.html.twig', ['user' => $user]);
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)trim($_POST['id']);
            $userService = new UserManager();
            $userService->delete($id);

            header('Location: /admin/user');
        }
    }
}
