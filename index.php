<?php
session_start();
require_once 'config/database.php';
require_once 'config/routes.php';
require_once 'core/Router.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';

// Autoload classes
spl_autoload_register(function ($class) {
    $paths = [
        'controllers/',
        'models/',
        'core/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            break;
        }
    }
});

$router = new Router();
$router->dispatch();
?>
