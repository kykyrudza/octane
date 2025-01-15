<?php

namespace Kernel\Application\Routing;

use Kernel\Application\Errors\ErrorHandler;
use Kernel\Application\Http\Request;
use Kernel\Application\Http\Response;

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function dispatch(array $routes, array $requestInfo, Request $request, Response $response): void
    {
        $this->routeDistributor($routes);

        $routeFound = false;

        try {
            foreach ($this->routes[$requestInfo['method']] as $route) {
                $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[^/]+)', $route->uri());
                $pattern = '/^'.str_replace('/', '\/', $pattern).'$/';

                if (preg_match($pattern, $requestInfo['uri'], $matches)) {
                    $routeFound = true;

                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    $request->merge($params);

                    if (is_array($route->getMiddleware())) {
                        foreach ($route->getMiddleware() as $middleware) {
                            $middlewareInstance = new $middleware;
                            $middlewareInstance->handle($request, $response);
                        }
                    }

                    if (is_array($route->handler())) {
                        [$controller, $action] = $route->handler();
                        $controllerInstance = new $controller;
                        $responseContent = call_user_func([$controllerInstance, $action], $request);
                    } else {
                        $closure = $route->handler();
                        $responseContent = call_user_func($closure, $request);
                    }

                    $response->setContent($responseContent);
                    break;
                }
            }

            if (! $routeFound) {
                abort(404, 'Route not found');
            }
        } catch (\Exception $e) {
            ErrorHandler::handleException($e);
        }
    }

    private function routeDistributor(array $routes): void
    {
        foreach ($routes as $route) {
            if ($route->method() === 'GET') {
                $this->routes['GET'][] = $route;
            } elseif ($route->method() === 'POST') {
                $this->routes['POST'][] = $route;
            }
        }
    }
}