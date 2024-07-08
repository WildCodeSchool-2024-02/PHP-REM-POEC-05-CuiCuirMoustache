<?php

namespace App\Controller;

//use App\Model\AuthModel;
use Twig\Environment;

class AuthController extends AbstractController
{

    protected Environment $twig;

    public function __construct(Environment $twig)
    {
      $this->twig = $twig;  
    }

    public function login() 
    {
        // Render login.html.twig using Twig
        return $this->twig->render('Auth/login.html.twig');
    }
}
