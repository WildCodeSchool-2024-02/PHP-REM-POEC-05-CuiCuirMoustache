<?php

namespace App\Controller\admin;

use App\Controller\AbstractController;

class LogController extends AbstractController
{
    /**
     * List le contenu du log
     */
    public function index()
    {
        $logFile = AbstractController::LOG_DIR;

        if (file_exists($logFile)) {
            $logContent = file_get_contents($logFile);
            return $this->twig->render('Admin/Log/index.html.twig', ['logContent' => nl2br($logContent)]);
        } else {
            echo "Log file does not exist.";
        }
    }
}
