<?php

namespace App\Controller;

use App\Service\Logger;
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

    protected Logger $logger;

    protected const LOG_DIR = __DIR__ . "/../../log/logfile.txt";

    public function __construct()
    {
        $loader = new FilesystemLoader(APP_VIEW_PATH);
        $this->logger = new Logger(self::LOG_DIR);
        $this->twig = new Environment(
            $loader,
            [
                'cache' => false,
                'debug' => true,
            ]
        );
        $this->twig->addExtension(new DebugExtension());

        //Ajout d'une fonction fitre a twig
        $filter = new TwigFilter('intToCurrency', 'App\\Helper\\Currency::intToCurrency');
        $this->twig->addFilter($filter);
    }
}
