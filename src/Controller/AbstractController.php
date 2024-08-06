<?php

namespace App\Controller;

use App\Service\LoggerConnection;
use App\Service\LoggerProduct;
use App\Service\LoggerCategory;
use App\Trait\CategoriesGetter;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

/**
 * Initialized some Controller common features (Twig...)
 */
abstract class AbstractController
{
    use CategoriesGetter;

    protected Environment $twig;
    protected LoggerConnection $loggerConnection;
    protected LoggerCategory $loggerCategory;
    protected LoggerProduct $loggerProduct;

    protected const LOG_DIR = __DIR__ . "/../../log/logfile.txt";

    public function __construct()
    {
        // Démarrer la session
        $this->startSession();

        // Vérifier si l'utilisateur est connecté
        $isLoggedIn = isset($_SESSION['user']);
        $isAdmin = $isLoggedIn && $_SESSION['user']['role'] === 'admin';

        $loader = new FilesystemLoader(APP_VIEW_PATH);
        $this->loggerConnection = new LoggerConnection(self::LOG_DIR);
        $this->loggerCategory = new LoggerCategory(self::LOG_DIR);
        $this->loggerProduct = new LoggerProduct(self::LOG_DIR);
        $this->twig = new Environment(
            $loader,
            [
                'cache' => false,
                'debug' => true,
            ]
        );
        $this->twig->addExtension(new DebugExtension());

        $this->twig->addGlobal("session", $_SESSION);

        // Ajouter isLoggedIn comme variable globale à Twig
        $this->twig->addGlobal('isLoggedIn', $isLoggedIn);
        $this->twig->addGlobal('isAdmin', $isAdmin);
        $this->twig->addGlobal('categoriesMenu', $this->getCategories());

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

    protected function requireAdmin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /login');
            exit();
        }
    }
}
