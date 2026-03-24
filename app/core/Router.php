<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, array $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post(string $uri, array $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    public function put(string $uri, array $action): void
    {
        $this->addRoute('PUT', $uri, $action);
    }

    private function addRoute(string $method, string $uri, array $action): void
    {
        $this->routes[$method][$uri] = $action;
    }

    public function dispatch(string $method, string $uri): void
    {
        $action = $this->routes[$method][$uri] ?? null;

        if (! $action) {
            http_response_code(404);
            echo '404 | Route not found';
            return;
        }

        [$controllerClass, $controllerMethod] = $action;
        $controller = new $controllerClass();
        $controller->{$controllerMethod}();
    }
}

