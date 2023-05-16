<?php declare(strict_types=1);

use App\Core\Renderer;
use App\Core\Router;

require '../vendor/autoload.php';


$response = Router::response();
$renderer = new Renderer(__DIR__ . '/../App/Views');

echo $renderer->render($response);