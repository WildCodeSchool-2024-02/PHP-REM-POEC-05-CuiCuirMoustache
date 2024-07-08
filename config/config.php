<?php

//Model (for connexion data, see unversionned db.php)
define('DB_USER', getenv('DB_USER') ? getenv('DB_USER') : 'manoah');
define('DB_PASSWORD', getenv('DB_PASSWORD') ? getenv('DB_PASSWORD') : 'kenan');
define('DB_HOST', getenv('DB_HOST') ? getenv('DB_HOST') : 'localhost');
define('DB_NAME', getenv('DB_NAME') ? getenv('DB_NAME') : 'database');

//View
define('APP_VIEW_PATH', __DIR__ . '/../src/View/');

// database dump file path for automatic import
define('DB_DUMP_PATH', __DIR__ . '/../database.sql');
