<?php

namespace App\Controller;

class LegalController extends AbstractController
{
    /**
     * Display Legal_NOTICE
     */
    public function index(): string
    {
        return $this->twig->render('Legal/index.html.twig');
    }
}
