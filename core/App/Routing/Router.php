<?php

namespace Kernel\Application\Routing;

use Exception;
use Kernel\Application\Errors\ErrorHandler;
use Kernel\Application\Http\Request;
use Kernel\Application\Http\Response;

/**
 * Class Router
 *
 * The Router class is responsible for routing HTTP requests to the appropriate handlers
 * based on the request method and URI. It matches routes, applies middleware, and executes
 * the corresponding controller or closure handler.
 *
 * @package Kernel\Application\Routing
 */
class Router
{
    /**
     * @var array The routes collection, categorized by HTTP method (GET, POST).
     */
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    /**
     * Dispatches the incoming request to the appropriate route handler.
     *
     * This method iterates over the available routes, finds a matching route
     * based on the request method and URI, and executes the route's handler
     * (either a closure or controller action). Middleware is applied to the
     * request before the handler is executed. If no matching route is found,
     * a 404 error is triggered.
     *
     * @param array $routes The defined routes for the application.
     * @param array $requestInfo The request information, including method and URI.
     * @param Request $request The current request instance.
     * @param Response $response The current response instance.
     * @throws Exception If an error occurs during route processing.
     */
    public function dispatch(array $routes, array $requestInfo, Request $request, Response $response): void
    {
        $this->routeDistributor($routes);

        $routeFound = false;

        try {
            foreach ($this->routes[$requestInfo['method']] as $route) {
                // Convert route URI with placeholders to regex pattern
                $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[^/]+)', $route->uri());
                $pattern = '/^'.str_replace('/', '\/', $pattern).'$/';

                // Check if the route matches the request URI
                if (preg_match($pattern, $requestInfo['uri'], $matches)) {
                    $routeFound = true;

                    // Extract parameters from the matched route and merge into request
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    $request->merge($params);

                    // Apply middleware to the request before handler execution
                    if (is_array($route->getMiddleware())) {
                        foreach ($route->getMiddleware() as $middleware) {
                            $middlewareInstance = new $middleware;
                            $middlewareInstance->handle($request, $response);
                        }
                    }

                    // Execute the route handler (controller action or closure)
                    if (is_array($route->handler())) {
                        [$controller, $action] = $route->handler();
                        $controllerInstance = new $controller;
                        $responseContent = call_user_func([$controllerInstance, $action], $request);
                    } else {
                        $closure = $route->handler();
                        $responseContent = call_user_func($closure, $request);
                    }

                    // Set the content to be returned in the response
                    $response->setContent($responseContent);
                    break;
                }
            }

            if (! $routeFound) {
                abort(404, 'Page not found');
            }
        } catch (Exception $e) {
            ErrorHandler::handleException($e);
        }
    }

    /**
     * Distributes routes into the appropriate HTTP method categories (GET, POST).
     *
     * This method classifies the routes into GET and POST routes and stores them
     * in the `$routes` property for later dispatching.
     *
     * @param array $routes The routes to be categorized.
     */
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
