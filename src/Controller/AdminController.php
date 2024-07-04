<?php

namespace App\Controller;

class AdminController extends AbstractController
{
    /**
     * Display home admin page
     */
    public function index(): string
    {
        return $this->twig->render('Admin/index.html.twig');
    }
}
