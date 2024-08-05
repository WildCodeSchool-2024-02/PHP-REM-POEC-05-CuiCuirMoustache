<?php

namespace App\Controller;

class PrivacyPolicyController extends AbstractController
{
    /**
     * Display about us
     */
    public function index(): string
    {
        return $this->twig->render('privacy-policy.html.twig');
    }
}
