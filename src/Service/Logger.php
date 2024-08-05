<?php

namespace App\Service;

use PDO;

class Logger
{
    protected $logFile;

    public function __construct($fileName)
    {
        $this->logFile = $fileName;
        // Crée le fichier log s'il n'existe pas déjà
        if (!file_exists($this->logFile)) {
            file_put_contents($this->logFile, "");
        }
    }

    public function log($message)
    {
        date_default_timezone_set("Europe/Paris");
        $timeStamp = date("Y-m-d H:i:s");
        $formattedMessage = "[" . $timeStamp . "] " . $message . PHP_EOL;
        file_put_contents($this->logFile, $formattedMessage, FILE_APPEND);
    }
}
