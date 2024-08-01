<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

/**
 * Initialized some Controller common features (Twig...)
 */
abstract class AbstractController
{
    protected Environment $twig;


    public function __construct()
    {
        // Démarrer la session
        $this->startSession();

        // Vérifier si l'utilisateur est connecté
        $isLoggedIn = isset($_SESSION['user']);

        $loader = new FilesystemLoader(APP_VIEW_PATH);
        $this->twig = new Environment(
            $loader,
            [
                'cache' => false,
                'debug' => true,
            ]
        );
        $this->twig->addExtension(new DebugExtension());

        // Ajouter isLoggedIn comme variable globale à Twig
        $this->twig->addGlobal('isLoggedIn', $isLoggedIn);

        //Ajout d'une fonction fitre a twig
        $filter = new TwigFilter('intToCurrency', 'App\\Helper\\Currency::intToCurrency');
        $this->twig->addFilter($filter);
    }

    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
