<?php declare(strict_types=1);

use App\Core\Renderer;
use App\Core\Router;

require '../vendor/autoload.php';

session_start();

$response = Router::response();
$renderer = new Renderer(__DIR__ . '/../App/Views');


//var_dump($_SESSION);

if ($response instanceof \App\Core\TwigView) {
    echo $renderer->render($response);
    unset($_SESSION['errors']);
}

if ($response instanceof \App\Core\Redirect) {
    header('Location: ' . $response->getLocation());
}


