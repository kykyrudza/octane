<?php

namespace Kernel\Application\Routing;

use Closure;

class RouteConfig
{
    private string $method;

    private string $uri;

    private Closure|array $handler;

    private ?string $name = null;

    private array $middleware = [];

    public function __construct(string $method, string $uri, Closure|array $handler)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->handler = $handler;
    }

    public function middleware(string|array $middleware = []): self
    {
        if (is_array($middleware)) {
            $this->middleware = $middleware;
        } else {
            $this->middleware = [$middleware];
        }

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function handler(): Closure|array
    {
        return $this->handler;
    }
}
