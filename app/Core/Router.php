<?php declare(strict_types=1);

namespace App\Core;

use App\Controllers\ArticleController;
use App\Controllers\LoginController;
use App\Controllers\RegisterController;
use App\Controllers\UserController;
use App\Repositories\Article\ArticleRepository;
use App\Repositories\Article\PdoArticleRepository;
use App\Repositories\User\PdoUserRepository;
use App\Repositories\User\UserRepository;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Router
{
    public static function response(): ?Response
    {
        $dotenv = Dotenv::createImmutable('../');
        $dotenv->load();

        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            ArticleRepository::class => new PdoArticleRepository(),
            UserRepository::class => new PdoUserRepository()
        ]);
        $container = $builder->build();

        $dispatcher = simpleDispatcher(function (RouteCollector $router) {

            // Users
            $router->addRoute('GET', '/', [ArticleController::class, 'index']);
            $router->addRoute('GET', '/users', [UserController::class, 'index']);
            $router->addRoute('GET', '/users/{id:\d+}', [UserController::class, 'show']);

            //Articles
            $router->addRoute('GET', '/articles', [ArticleController::class, 'index']);
            $router->addRoute('GET', '/articles/{id:\d+}', [ArticleController::class, 'show']);
            $router->addRoute('GET', '/articles/post', [ArticleController::class, 'create']);
            $router->addRoute('POST', '/articles', [ArticleController::class, 'store']);

            $router->addRoute('GET', '/articles/{id:\d+}/edit', [ArticleController::class, 'edit']);
            $router->addRoute('POST', '/articles/{id:\d+}', [ArticleController::class, 'update']);

            $router->addRoute('POST', '/delete', [ArticleController::class, 'delete']);

            $router->addRoute('GET', '/register', [RegisterController::class, 'showForm']);
            $router->addRoute('POST', '/register', [RegisterController::class, 'save']);

            $router->addRoute('GET', '/login', [LoginController::class, 'showForm']);
            $router->addRoute('POST', '/login', [LoginController::class, 'login']);


            $router->addRoute('GET', '/logout', [RegisterController::class, 'logout']);

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
                return new TwigView('notFound', []);

            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];

                return null;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                [$controllerName, $methodName] = $handler;

                $controller = $container->get($controllerName);

                /** @var TwigView $response */
                $response = $controller->{$methodName}($vars);

                return $response;
        }

        return null;
    }
}