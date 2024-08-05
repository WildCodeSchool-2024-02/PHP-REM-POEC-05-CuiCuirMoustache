<?php

namespace App\Controller;

class AboutController extends AbstractController
{
    /**
     * Display about us
     */
    public function index(): string
    {
        return $this->twig->render('Infos/index.html.twig');
    }
}
