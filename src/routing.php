<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Controller\AuthController;
use App\Model\AuthModel;

// Configure Twig
$loader = new FilesystemLoader(__DIR__ . '/../src/View');
$twig = new Environment($loader);

// Create PDO instance (assuming this is used in AuthModel constructor)
$pdo = new PDO("mysql:host=" . APP_DB_HOST . ";dbname=" . APP_DB_NAME, APP_DB_USER, APP_DB_PASSWORD);

// Create AuthModel instance
$authModel = new AuthModel($pdo);

// Get the required route (without query string) and remove trailing slashes
$route = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');

// $routes comes from 'routes.php' required here
$routes = require_once __DIR__ . '/../src/routes.php';

// If required route is not in $routes, return a 404 Page not found error
if (!key_exists($route, $routes)) {
    header("HTTP/1.0 404 Not Found");
    echo '404 - Page not found';
    exit();
}

// Get the matching route in $routes array
$matchingRoute = $routes[$route];

// Only handle the 'login' route
if ($route === 'login') {
    // Get the FQCN of the controller associated with the matching route
    $controllerClass = 'App\\Controller\\' . $matchingRoute[0];
    // Get the method associated with the matching route
    $method = $matchingRoute[1];
    // Instance the controller, call the method with given parameters
    try {
        // Execute the controller
        // Pass both $twig and $authModel instances to the controller
        $controller = new $controllerClass($twig, $authModel);
        echo $controller->$method();
    } catch (Exception $e) {
        // If an exception is thrown during controller execution
        header("HTTP/1.0 500 Internal Server Error");
        echo '500 - Internal Server Error';
        echo '<pre>' . $e->getMessage() . '</pre>'; // Optionally, display the error message
        exit();
    }
} else {
    // Handle other routes if needed
    header("HTTP/1.0 404 Not Found");
    echo '404 - Page not found';
    exit();
}
