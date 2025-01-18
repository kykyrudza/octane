<?php

namespace Kernel\Application\Http;

use Exception;
use Kernel\Application\Errors\ErrorHandler;
use Kernel\Application\Routing\Router;

/**
 * Class Kernel
 *
 * The Kernel class is responsible for initializing and running the application. It handles routing,
 * dispatching requests, and sending responses. In case of errors or exceptions, it captures them
 * and renders an appropriate error page.
 *
 * @package Kernel\Application\Http
 */
readonly class Kernel
{
    /**
     * Kernel constructor.
     *
     * Initializes the Kernel with the provided request and response objects.
     *
     * @param Request $request The HTTP request object.
     * @param Response $response The HTTP response object.
     */
    public function __construct(
        private Request $request,
        private Response $response,
    ) {}

    /**
     * Runs the application.
     *
     * This method is responsible for dispatching the request, handling routing,
     * and sending the response. It starts output buffering, catches any exceptions,
     * and uses the ErrorHandler to display a user-friendly error page in case of failure.
     *
     * @return void
     */
    public function run(): void
    {
        ob_start();
        try {
            // Get the list of routes
            $routes = $this->getRoutes();

            // Get the request info from the request object
            $requestInfo = $this->request->getRequestInfo();

            // Initialize the Router and dispatch the request
            $router = new Router;
            $router->dispatch($routes, $requestInfo, $this->request, $this->response);

            // Send the response to the client
            $this->response->send();
        } catch (Exception $e) {
            // Handle any exceptions that occur during execution
            new ErrorHandler('Internal Server Error', 500, $e->getFile(), $e->getLine());
        }
        ob_end_flush();
    }

    /**
     * Retrieves the application routes.
     *
     * This method includes the `web.php` file containing the application's routes and returns
     * them as an array.
     *
     * @return array The application routes.
     */
    private function getRoutes(): array
    {
        return require_once APP_ROOT.'/routes/web.php';
    }
}
