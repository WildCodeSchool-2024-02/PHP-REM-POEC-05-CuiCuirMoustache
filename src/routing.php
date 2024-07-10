<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Configure Twig
$loader = new FilesystemLoader(__DIR__ . '/../src/View');
$twig = new Environment($loader);

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

// Get the FQCN of the controller associated with the matching route
$controllerClass = 'App\\Controller\\' . $matchingRoute[0];
// Get the method associated with the matching route
$method = $matchingRoute[1];
// Get the queryString values configured for the matching route (in $_GET superglobal).
// If there are additional queryString parameters, they are ignored here, and should be
// directly managed in the controller
$parameters = [];
foreach ($matchingRoute[2] ?? [] as $parameter) {
    if (isset($_GET[$parameter])) {
        $parameters[] = $_GET[$parameter];
    }
}

// Instance the controller, call the method with given parameters
// Controller method will return a Twig template (HTML string) which is displayed here
try {
    // Execute the controller
    // Pass the $twig instance to the controller
    $controller = new $controllerClass($twig);
    echo $controller->$method(...$parameters);
} catch (Exception $e) {
    // If an exception is thrown during controller execution
    header("HTTP/1.0 500 Internal Server Error");
    echo '500 - Internal Server Error';
    echo '<pre>' . $e->getMessage() . '</pre>'; // Optionally, display the error message
    exit();
}
