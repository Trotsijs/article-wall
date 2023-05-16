<?php declare(strict_types=1);

namespace App\Core;

use App\Controllers\ArticleController;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Router
{
    public static function response(): ?TwigView
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $router) {
            $router->addRoute('GET', '/', [ArticleController::class, 'index']);
            $router->addRoute('GET', '/articles', [ArticleController::class, 'index']);
            $router->addRoute('GET', '/users', [ArticleController::class, 'users']);
            $router->addRoute('GET', '/article/{id:\d+}', [ArticleController::class, 'singleArticle']);
            $router->addRoute('GET', '/users/{id:\d+}', [ArticleController::class, 'singleUser']);

        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                return null;

            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];

                return null;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                [$controllerName, $methodName] = $handler;
                /** @var TwigView $response */

                $response = (new $controllerName)->{$methodName}($vars);

                return $response;
        }

        return null;
    }
}