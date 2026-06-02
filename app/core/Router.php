<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, array $action, array $middleware = []): void
    {
        $this->addRoute('GET', $uri, $action, $middleware);
    }

    public function post(string $uri, array $action, array $middleware = []): void
    {
        $this->addRoute('POST', $uri, $action, $middleware);
    }

    public function put(string $uri, array $action, array $middleware = []): void
    {
        $this->addRoute('PUT', $uri, $action, $middleware);
    }

    private function addRoute(string $method, string $uri, array $action, array $middleware = []): void
    {
        $this->routes[$method][$uri] = [
            'action' => $action,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        $route = $this->routes[$method][$uri] ?? null;

        if (! is_array($route)) {
            http_response_code(404);
            echo '404 | Route not found';
            return;
        }

        foreach ($route['middleware'] as $middlewareClass) {
            $middleware = new $middlewareClass();
            $middleware->handle($method, $uri);
        }

        $action = $route['action'];
        [$controllerClass, $controllerMethod] = $action;
        $controller = new $controllerClass();
        $controller->{$controllerMethod}();
    }
}

