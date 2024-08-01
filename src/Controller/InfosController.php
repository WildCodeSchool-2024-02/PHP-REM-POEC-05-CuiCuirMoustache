<?php

namespace App\Controller;

class InfosController extends AbstractController
{
    /**
     * Display home admin page
     */
    public function index(): string
    {
        return $this->twig->render('Infos/index.html.twig');
    }
}
